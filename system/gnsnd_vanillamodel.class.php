<?php
class GNSND_VanillaModel {
    /**
     * 
     * 
     */
    protected $_model;
    protected $_result;
    protected $_query;
    protected $_table;
    
    // used in constructing queries
    protected $_orderBy;
    protected $_order;
    protected $_extraConditions;
    protected $_describe = array();
    
    public $all; // will be used to store an array if we fetch multiple objects
    public $object_fields = array(); 
    
    public $inflect;
    public $cache;
    
    function __construct() {
        
        $this->db = new GNSND_Database();
        $this->inflect = Inflection::get_instance();
        $this->cache = Cache::get_instance();
        
        $this->_model = get_class($this);
        
        $this->_table = strtolower($this->inflect->pluralize($this->_model));
        
        if(!$this->_abstract) // if it's an 'abstract' class, there'll be no database to describe
        {
            $this->_describe();
        }
    }
    
    public function save() // save this model to the DB... $this->model_name->save() is so useful (fields are set as attributes of the model)
    {
        $query = '';
        if (isset($this->id))
        {
            // we're updating not inserting
            $updates = '';
            
            foreach ($this->_describe as $field)
            {
                if ($this->$field)
                {
                    $updates .= '`'.$field.'` = '.$this->_field_to_param($field).',';
                }
            }

            $updates = substr($updates,0,-1); // remove final comma
            $query = 'UPDATE ' . $this->_table . ' SET ' . $updates . ' WHERE `id`=' . (int)$this->id . '';
            $run_query = $this->db->prepare($query);
            
            // let's go through and bind our parameters
            foreach ($this->_describe as $field) {
                if ($this->$field) {
                    $run_query->bindParam($this->_field_to_param($field), $this->$field);
                }
            }
        }
        else // insert new one!
        {
            $fields = '';
            $values = '';
            
            foreach ($this->_describe as $field) {
                if($field != 'id') // hack? Don't put in ID for new entries
                {
                    $fields .= "`".$field.'`,';
                    $values .= "".$this->_field_to_param($field).", ";
                }
            }
            
            $values = substr($values,0,-2); // remove final comma
            $fields = substr($fields,0,-1); // remove final comma

            $query = "INSERT INTO ".$this->_table." ($fields) VALUES ($values)";
            $run_query = $this->db->prepare($query);
            
            foreach ($this->_describe as $field) {
                if ($field != 'id') {
                    $run_query->bindParam($this->_field_to_param($field), $this->$field);
                }
            }
        }
        
        if($result = $run_query->execute()) // execute it!
        {
            if(!isset($this->id)) // update our ID to the newly inserted row!
            {
                $this->id = $this->db->lastInsertId('id');
            }
            return true;
        }
        else
        {
            return false;
        }
    }
    
    // check if an item actually exists by checking if it has an ID
    public function exists()
    {
        if(isset($this->id) && $this->id != 0) // if we have an ID and it's not 0, then we're onto a winner here!
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function load($id = 0)
    {
        if(!$id || !is_int($id))
        {
            return false; // ohnoes...
        }
        else
        {            
            $query = 'SELECT * FROM ' . $this->_table . ' WHERE id = :id';
            
            $run_query = $this->db->prepare($query);
            $run_query->bindParam(':id', $id, PDO::PARAM_INT);
            $run_query->execute();
            
            $result = $run_query->fetch(PDO::FETCH_ASSOC); // get our next row
            
            if($result) // if we have a result
            {
                foreach($result as $field => $value) // load our values into the attributes for this object
                {
                    $this->$field = $value;
                }
                return true;
            }
            else
            {
                return false; // return false, bad times.
            }
        }
    }
    
    // deletes the current item
    public function delete() 
    {
        if($this->exists())
        {
            // delete
            $query = 'DELETE FROM ' . $this->_table . ' WHERE id = :id';
            
            $run_query = $this->db->prepare($query);
            $run_query->bindParam(':id', $this->id, PDO::PARAM_INT);
            
            
            if($result = $run_query->execute())
            {
                $this->clear();
                return true;
            }
            else
            {
                return false;
            }
        }
        else // can't delete - doesn't exist!
        {
            return false;
        }
    }
    
    // clear our data
    public function clear()
    {
        // run through, set all fields to null
        foreach ($this->_describe as $field)
        {
            $this->$field = null;
            $this->object_fields[$field] = null; // separate array of fields too
        }
    }
    
    // function to eventually load an item by any field considered to be unique... (such as a URL maybe?)
    public function load_by_field($field = '', $value = false)
    {
        
    }
    
    function load_helper($helper, $key = 0)
    {
        load_helper($helper); // global function to include the right file
        
        $helper_name = SYSTEM_PREPEND . $helper;
        
        $var_name = $key;
        
        if(is_int($key))
        {
            $var_name = strtolower($helper_name);
        }
        if(!isset($this->$var_name)) // only set if it doesn't already exist as $var_name so we don't overwrite new ones
        {
            $this->$var_name = new $helper_name;
        }
        
    }
    
    function load_library($library, $key = 0)
    {
        load_library($library); // global function to include the right file
        
        $library_name = SYSTEM_PREPEND . $library;
        
        $var_name = $key;
        
        if(is_int($key))
        {
            $var_name = strtolower($library_name);
        }
        
        if(!isset($this->$var_name)) // only set if it doesn't already exist as $var_name so we don't overwrite new ones
        {
            $this->$var_name = new $library_name;
        }
    }
    
    protected function _field_to_param($field)
    {
        $param = ':';
        $param .= $field;
        return $param;
    }
    
    protected function _describe()
    {
        $this->cache = Cache::get_instance();
        
        $this->_describe = $this->cache->get('describe' . $this->_table); // load from cache

        if (!$this->_describe) // if it doesn't exist - let's get it!
        {
            $this->_describe = array();
            $query = $this->db->prepare('DESCRIBE ' . $this->_table);
            $query->execute();
            $this->_result = $query->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($this->_result as $result)
            {
                array_push($this->_describe,$result['Field']);
            }
            
            $this->cache->set('describe' . $this->_table,$this->_describe);
        }
        foreach ($this->_describe as $field)
        {
            $this->$field = null;
            $this->object_fields[$field] = null; // separate array of fields too
        }
    }
    
    function __destruct()
    {
        
    }
}