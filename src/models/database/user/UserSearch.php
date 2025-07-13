<?php

namespace app\models\database\user;

use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $created_at_start;
    public $created_at_end;

    public function rules()
    {
        return [
            [[
                'name',
                'surname',
                'email',
                'phone_number',
                'created_at_start',
                'created_at_end',
                'id',
                'active',
                'role',
            ], 'safe'],
            [['created_at_start', 'created_at_end'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function scenarios()
    {
        return [self::SCENARIO_DEFAULT => [
            'name',
            'surname',
            'email',
            'phone_number',
            'created_at_start',
            'created_at_end',
            'id',
            'active',
            'role',
        ]];
    }

    public function search($itemCount): ActiveDataProvider
    {
        $query = self::find();
        if ($this->name != null) {
            $query = $query->andWhere(['like', 'name', $this->name]);
        }

        if ($this->surname != null) {
            $query = $query->andWhere(['like', 'surname', $this->surname]);
        }

        if ($this->email != null) {
            $query = $query->andWhere(['like', 'email', $this->email]);
        }

        if ($this->phone_number != null) {
            $query = $query->andWhere(['like', 'phone_number', $this->phone_number]);
        }

        if ($this->active != null) {
            $query = $query->andWhere(['=', 'active', (int)$this->active]);
        }

        if ($this->id != null) {
            $query = $query->andWhere(['=', 'id', (int)$this->id]);
        }
        
        if ($this->role != null && array_key_exists($this->role, Role::getRoles())) {
            $query = $query->andWhere(['=', 'role', $this->role]);
        }
        if ($this->created_at_start != null) { //&& DBDate::validateDate($this->created_at_start)
            $query = $query->andWhere(['>=', 'created_at', $this->created_at_start]);
        }
        if ($this->created_at_end != null) { // && DBDate::validateDate($this->created_at_end)
            $query = $query->andWhere(['<=', 'created_at', $this->created_at_end]);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $itemCount,
            ]
        ]);
    }
}