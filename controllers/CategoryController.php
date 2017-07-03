<?php

namespace app\controllers;

use app\models\Category;
use Yii;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\db\Query;

class CategoryController extends Controller
{

    /**
     * Вывод дерева меню
     * @return string
     */
    function actionIndex(){

            $cats = Category::find()
                ->where(['level' => 0])
                ->all();

          //  if(!$cats) return 'Создайте корневую директорию';
      
        return $this->render('index', ['cats' => $cats]);
    }

    /**
     * Добавление пункта меню
     * @return string|void|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    function actionAdd(){

        $model = new Category();

        //return var_dump(Yii::$app->request->post());

        if($model->load(Yii::$app->request->post())){
            if (Yii::$app->request->post('Category')['rootCat'] === '') {
                $model = new Category([
                        'title' => Yii::$app->request->post('Category')['title'],
                        'level' => 0,
                        'next' => 1,
                        'tree' => 0
                    ]
                );

                try {
                    if ($model->save()) {
                        $model->tree = $model->id;
                        $model->update();
                    }
                } catch (ErrorException $e) {
                    return $e->getMessage();
                }

                return $this->redirect('index');

            }

            else {


                $rootCategory = Category::findOne((int)Yii::$app->request->post('Category')['rootCat']);

               // return var_dump($rootCategory);
                if($rootCategory){
                    $model = new Category([
                        'title' => Yii::$app->request->post('Category')['title'],
                        'level' => $rootCategory->level + 1,
                        'next' => $rootCategory->next,
                        'tree' => $rootCategory->id
                    ]);

                    try {
                        if ($model->save()) {
                            //return var_dump($rootCategory);
                            $rootCategory->next = +1;
                            $rootCategory->update();
                        }
                    } catch (ErrorException $e) {
                        return $e->getMessage();
                    }
                }

                else return var_dump($rootCategory);

                return $this->redirect('index');

            }

            
        }
        else {
            return $this->render('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Вывод список для редактирования
     * @return string
     */
    function actionEdit(){
        $cats = Category::find();

        if(!$cats) return 'Их нет';

        $dataProvider = new ActiveDataProvider([
            'query' => $cats,
        ]);

        return $this->render('list', ['cats' => $dataProvider]);
        
    }

    /**
     * Редактирование пункта меню
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    function actionUpdate($id){
        $model = $this->loadModel($id);


        if ($model->load(Yii::$app->request->post())) {
            //return var_dump($model);

            if (Yii::$app->request->post('Category')['rootCat'] === '') {

                if($model->level == 0) {
                    return $this->render('error', [
                        'error' => 'Эта операция не позволена!',
                    ]);
                }

                $model->title = Yii::$app->request->post('Category')['title'];
                $model->level = 0;
                $model->next = 1;
                $model->tree = $id;

                try {
                    if ($model->update()) {

                        $this->updateChildren($model->level, $id);

                        return $this->redirect('index');
                    }
                } catch (ErrorException $e) {
                    return $e->getMessage();
                }
                

            }

            else {

                $rootCategory = Category::findOne((int)Yii::$app->request->post('Category')['rootCat']);

                //$model_child = Category::find()->where(['tree' => $model->tree])->one();

                if($rootCategory->tree == $model->tree) {
                    return $this->render('error', [
                        'error' => 'Эта операция не позволена!',
                    ]);
                }
                
                $model->title = Yii::$app->request->post('Category')['title'];
                $model->level = $rootCategory->level + 1;
                $model->next = 1;
                $model->tree = $rootCategory->id;

                try {
                    if ($model->update()) {

                        $this->updateChildren($model->level, $id);

                        return $this->redirect('index');
                    }
                    else var_dump($model->getErrors());
                } catch (ErrorException $e) {
                    return $e->getMessage();
                }
            }
        } else {

            return $this->render('_form', [
                'model' => $model,

            ]);
        }
    }

    /**
     * Удаление пунктов меню
     * @param $id
     * @return void|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\web\HttpException
     */
    public function actionDelete($id){
        $model = $this->loadModel($id);

        if($model) {
            if($model->delete()) {
                $children = Category::find()->where("tree = $model->id AND level > $model->level")->all();

                foreach ($children as $child){
                    $this->actionDelete($child->id);
                }
                return $this->redirect('index');
            }

        }

        return $this->redirect('index');

    }

    /**
     * Загружает запись модели текущего контроллера по айдишнику
     * @param $id
     * @return null|static
     * @throws \yii\web\HttpException
     */
    public function loadModel($id)
    {

        $model = Category::findOne($id);

        if ($model === null)
            throw new \yii\web\HttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function updateChildren($root_level, $id){
        $cats = Category::find()->where("tree = " . $id . " AND id <> ". $id)->all();

        if(!$cats) return false;

        foreach ($cats as $cat){
            $cat->level = $root_level + 1;

           if($cat->update(false))
               $this->updateChildren($cat->level, $cat->id);

        }
        return false;
    }
    
    
}