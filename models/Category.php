<?php

namespace app\models;

use Yii;
use yii\base\ErrorException;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 * @property int $next
 * @property int $level
 * @property int $tree
 */
class Category extends \yii\db\ActiveRecord
{
    public $rootCat;
    private static $menu;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'next', 'level', 'tree'], 'required'],
            [['next', 'level', 'tree'], 'integer'],
            [['title'], 'string', 'max' => 8, 'message' => 'Слишко длинно'] ,
            //['rootCat', 'compare', 'compareAttribute' => 'title'],
            //['rootCat', 'validateRootCat', 'skipOnEmpty' => false, 'skipOnError' => false],

            /*['state', 'required', 'when' => function ($model) {
                return $model->tree == 'USA';
                 },
               // 'whenClient' => "function (attribute, value) {
                //     return $('#country').val() == 'USA'; }"
            ]*/

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'next' => 'Next',
            'level' => 'Level',
            'tree' => 'Tree',
        ];
    }

    /**
     * Получение дочернего пункта меню
     * @param $id
     * @param $level
     * @return string
     */
    public function getChild($id, $level){
        if($level == 0) self::$menu = '';
        $level++;

        $roots = Category::find()->where(['tree' => $id, 'level' => $level])->all();

        //var_dump($roots);

        if(!$roots) return '';

        //echo self::$menu;

        self::$menu .= '<ul>';

        foreach ($roots as $root){

            if(!$root->haveChild($root->level))

                self::$menu .= '<li><a href="#">'.$root->title.'</a></li>';

            else {
                try {
                    self::$menu .= '<li><a href="#">'
                        .$root->title. '
                    </a>';
                    $this->getChild($root->id, $root->level);
                    self::$menu .= '</li>';
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            }

        }

        self::$menu .= '</ul>';
        
        return self::$menu;

    }

    /**
     * Пороверка, есть ли дочерний пункт меню
     * @param $parent_level
     * @return bool
     */
    public function haveChild($parent_level){
        
        $child_level = $parent_level + 1;
        
        $this_tree = self::find()->where(['tree' => $this->id, 'level' => $child_level])->all();
        
        if(count($this_tree)>0) 
            return true;
        
        return false;
    }

    public function validateRootCat($attribute, $params)
    {
        if (in_array($this->$attribute, ['USA', 'Web'])) {
            $this->addError($attribute, 'Страна должна быть либо "USA" или "Web".');
        }

    }
}
