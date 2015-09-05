<?php

class MainController extends Controller {

    public $layout = "webapp";

    public function actionIndex() {

        if (Yii::app()->session['status'] != 'A') {
            $product = new Product();
            //$this->output_system($data, 'web_system/home_system', $head);
            $data['last_product'] = $product->_get_last_product();
            $data['sale_product'] = $product->_get_sale_product();

            $this->render('//main/home', $data);
        } else {
            $this->actionBackend();
        }
    }

    public function actionBackend() {
        $this->redirect(array('backend/backend/index'));
    }

    public function actionFrom_login() {
        $this->renderPartial('//main/from_login');
    }

    public function actionLogin() {
        $email = $_POST['login_email'];
        $password = $_POST['login_password'];

        $sql = "SELECT pid,name,lname,password,status,email,address,tel
                FROM masuser
                WHERE email = '$email' AND password = '$password' ";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if (!empty($result)) {
            $row = $result;
            $user = $row['name'] . ' ' . $row['lname'];
            $status = $row['status'];
            $pid = $row['pid'];

            Yii::app()->session['username'] = $user;
            Yii::app()->session['status'] = $status;
            Yii::app()->session['pid'] = $pid;

            //เก็บค่าประวัติทั้งหมดไว้ใน session
            Yii::app()->session['member'] = $row;

            //ดึงรหัสการสั่งซื้อมาแสดง
            $Order = new Orders();
            $max_order_id = $Order->Get_status_last_order($pid);
            Yii::app()->session['order_id'] = $max_order_id;

            echo "success";
        } else {
            echo "nosuccess";
        }
    }

    public function actionRegister() {
        $web = new Configweb_model();
        $data['mas_pername'] = $web->pername();
        $data['id'] = $web->autoId('masuser', 'pid', '10');

        $this->render('//frontend/main/register', $data);
    }

    public function actionSave_register() {
        if ($_POST['year'] != '' && $_POST['month'] != '' && $_POST['day']) {
            $birth = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
        } else {
            $birth = '';
        }
        $columns = array(
            "pid" => $_POST['pid'],
            "alias" => $_POST['alias'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "name" => $_POST['name'],
            "lname" => $_POST['lname'],
            "birth" => $birth,
            "sex" => $_POST['sex'],
            "tel" => $_POST['tel'],
            "create_date" => date("Y-m-d H:i:s"),
            "d_update" => date("Y-m-d H:i:s")
        );

        Yii::app()->db->createCommand()
                ->insert("masuser", $columns);

        $this->redirect(array('main/register_success'));
    }

    public function actionRegister_success() {
        $this->render('//main/register_success');
    }

    public function from_edit_register() {
        $deta['mas_pername'] = $this->p_db->pername();
        $page = "web_system/from_edit_register";
        $head = "แก้ไขบัญชีผู้ใช้";
        $deta['error'] = '';
        $this->output_system($deta, $page, $head);
    }

    /*     * ********************************************* คู่มือ ************************************ */

    public function manual() {
        $deta = '';
        $page = 'web_system/manual';
        $head = 'ขั้นตอนการใช้งานโปรแกรม';

        $this->output_system($deta, $page, $head);
    }

    public function contact() {
        $deta = '';
        $page = 'web_system/contace';
        $head = 'ติดต่อเรา';

        $this->output_system($deta, $page, $head);
    }

    public function show_product_all($type_id = '') {
        $data['type_name'] = $this->product->get_type_name($type_id);
        $data['product'] = $this->product->get_product_all($type_id);
        $data['count_product_type'] = $this->product->get_count_product_type($type_id);
        $page = "web_system/show_product_all";
        $head = "<span class='label label-danger' style='font-size:20px; font-weight: bold;'>" . $data['type_name'] . "</span>";
        $head .= " จำนวน <font style='color:red;'>" . $data['count_product_type'] . "</font> รายการ";

        $this->output_webapp($data, $page, $head);
    }

}