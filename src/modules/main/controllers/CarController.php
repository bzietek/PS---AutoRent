<?php

namespace app\modules\main\controllers;

use app\controllers\SiteController;
use app\models\database\user\Role;
use app\models\database\car\Car;
use Yii;

class CarController extends SiteController
{
    protected $allowedRoles = [Role::ROLE_SERVICE_ENGINEER];
    protected $serviceEngineerActions = ['add', 'index'];

    public function actionAdd()
    {
        $model = new Car();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Samochód został dodany.');
                return $this->redirect(['car/add']);
            } else {
                // Dodaj to:
                Yii::$app->session->setFlash('error', implode('<br>', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $model->getErrors())));
            }
        }

        return $this->render('add', [
            'model' => $model,
        ]);
    }


    public function actionIndex()
    {
        $cars = Car::find()->all();

        return $this->render('index', [
            'cars' => $cars,
        ]);
    }

    public function actionDelete($id)
    {
        $car = Car::findOne($id);
        if ($car) {
            $car->delete();
            Yii::$app->session->setFlash('success', 'Samochód został usunięty.');
        } else {
            Yii::$app->session->setFlash('error', 'Nie znaleziono samochodu.');
        }
        return $this->redirect(['car/index']);
    }

}
