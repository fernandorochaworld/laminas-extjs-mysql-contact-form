<?php

namespace Form\Model;

class Form {
    
    public $id;
    public $name;
    public $priority_id;
    public $company;
    public $email;
    public $birthdate;
    public $profession;
    public $notes;
    public $phone_home;
    public $phone_business;
    public $phone_mobile;
    public $fax;
    public $biography;

    public $created_at;
    public $updated_at;

    public function exchangeArray($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->priority_id = $data['priority_id'];
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updated_at = $data['updated_at'] ?? date('Y-m-d H:i:s');

        $this->company = $data['company'] ?: null;
        $this->email = $data['email'] ?: null;
        $this->birthdate = $data['birthdate'] ?: null;
        $this->profession = $data['profession'] ?: null;
        $this->notes = $data['notes'] ?: null;
        $this->phone_home = $data['phone_home'] ?: null;
        $this->phone_business = $data['phone_business'] ?: null;
        $this->phone_mobile = $data['phone_mobile'] ?: null;
        $this->fax = $data['fax'] ?: null;
        $this->biography = $data['biography'] ?: null;
    }

    public function getArrayCopy() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'priority_id' => $this->priority_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'company' => $this->company,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'profession' => $this->profession,
            'notes' => $this->notes,
            'phone_home' => $this->phone_home,
            'phone_business' => $this->phone_business,
            'phone_mobile' => $this->phone_mobile,
            'fax' => $this->fax,
            'biography' => $this->biography,
        ];
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */ 
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @return  self
     */ 
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */ 
    public function getPriorityId()
    {
        return $this->priority_id;
    }

    /**
     * Set the value of priority_id
     *
     * @return  self
     */ 
    public function setPriorityId($priority_id)
    {
        $this->priority_id = $priority_id;

        return $this;
    }



    /**
     * Get the value of company
     */ 
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @return  self
     */ 
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of birthdate
     */ 
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set the value of birthdate
     *
     * @return  self
     */ 
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get the value of profession
     */ 
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set the value of profession
     *
     * @return  self
     */ 
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get the value of notes
     */ 
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set the value of notes
     *
     * @return  self
     */ 
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get the value of phone_home
     */ 
    public function getPhoneHome()
    {
        return $this->phone_home;
    }

    /**
     * Set the value of phone_home
     *
     * @return  self
     */ 
    public function setPhoneHome($phone_home)
    {
        $this->phone_home = $phone_home;

        return $this;
    }

    /**
     * Get the value of phone_business
     */ 
    public function getPhoneBusiness()
    {
        return $this->phone_business;
    }

    /**
     * Set the value of phone_business
     *
     * @return  self
     */ 
    public function setPhoneBusiness($phone_business)
    {
        $this->phone_business = $phone_business;

        return $this;
    }

    /**
     * Get the value of phone_mobile
     */ 
    public function getPhoneMobile()
    {
        return $this->phone_mobile;
    }

    /**
     * Set the value of phone_mobile
     *
     * @return  self
     */ 
    public function setPhoneMobile($phone_mobile)
    {
        $this->phone_mobile = $phone_mobile;

        return $this;
    }

    /**
     * Get the value of fax
     */ 
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set the value of fax
     *
     * @return  self
     */ 
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get the value of biography
     */ 
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set the value of biography
     *
     * @return  self
     */ 
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }
}