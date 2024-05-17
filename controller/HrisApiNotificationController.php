<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class NotificationController extends AppController{

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Auth');
        $this->hrisApiClients = TableRegistry::get('api_clients');
        $this->uploadPath = WWW_ROOT . 'uploads' . DS . 'app_notification' . DS;
        $this->uploadPathView = '/uploads' . DS . 'app_notification' . DS;

        $this->moduleArray = API_NOTIFICATION_MODULES;

        $this->ApiNotification = TableRegistry::get('hris_api_notification');
        $this->ApiNotificationRoleColor = TableRegistry::get('hris_api_notification_role_color');
        $this->ApiNotificationImage = TableRegistry::get('hris_api_notification_image');


    }

    public function index(){
        
        $Notification = $this->ApiNotification->find('all')
                            ->select(['hris_api_notification.id','hris_api_notification.client_id',
                            'hris_api_notification.module','hris_api_notification.default_male','hris_api_notification.default_female','hris_api_notification.status','hris_api_notification.created_on',
                            'hris_api_notification.updated_on','clientName'=>'hac.name','imagePath' => 'hris_api_notification_image.file_path'])
                            ->join([
                                    'hac' => [
                                        'table' => 'api_clients',
                                        'type' => 'LEFT',
                                        'conditions' => [
                                            'hac.id' =>  new \Cake\Database\Expression\IdentifierExpression('hris_api_notification.client_id')
                                        ]
                                    ],'hris_api_notification_image' => [ 
                                        'table' => 'hris_api_notification_image',
                                        'type' => 'LEFT',
                                        'conditions' => [
                                            'hris_api_notification_image.notification_id = hris_api_notification.id'
                                        ]
                                    ]
                                ])->group('hris_api_notification.id')->enableHydration(false)->toArray(); 
        $moduleArray = $this->moduleArray; 
        $uploadPath = $this->uploadPathView;



        $this->set(compact('Notification','moduleArray','uploadPath'));
    }
    

    public function add()
{
    $notification = $this->ApiNotification->newEntity();
    if ($this->request->is('post')) {
        $employee_id = $this->Auth->user('emp_id');
        $getData = $this->request->getData();
        $client_id = $getData['client_id'];

        $existingNotification = $this->ApiNotification->find()
            ->where(['client_id' => $client_id])
            ->first();

        if ($existingNotification) {
            $this->Flash->error(__('Client already exists. Please choose a different client.'));
            return $this->redirect(['action' => 'index']);
        }

         
        $insertData = [];
        foreach ($getData as $key => $data) {
            if(!isset($getData[$key]['tmp_name'])){
                $insertData[$key] = (is_array($data)) ? implode(',', $data) : $data;
            }
        }
        $insertData['module'] = $getData['modules'];
        $insertData['created'] = $employee_id;
        $insertData['created'] = date('Y-m-d H:i:s');


        Male = $getData['default_male']['name'];
        if(!empty(Male)){
            $attachment = $this->uploadAttachments($getData['default_male']);
            if(!empty($attachment)){
                $insertData['default_male'] = $attachment;
            }else{
                $insertData['default_male'] = '';
            }
        }

        $defaultFemale = $getData['default_female']['name'];
        if(!empty($defaultFemale)){
            $attachment = $this->uploadAttachments($getData['default_female']);
            if(!empty($attachment)){
                $insertData['default_female'] = $attachment;
            }else{
                $insertData['default_female'] = '';
            }
        }
        $fileName = $getData['file_path']['name'];
            if(!empty($fileName)){
                $attachment = $this->uploadAttachments($getData['file_path']);
                if(!empty($attachment)){
                    $insertData['file_path'] = $attachment;
                }else{
                    $insertData['file_path'] = '';
                }
            }

        $notification = $this->ApiNotification->patchEntity($notification, $insertData);
        if ($this->ApiNotification->save($notification)) {
            
            $lastInsertedId = $notification->id;
            $notificationImageTable = TableRegistry::get('hris_api_notification_image');
            $notificationImage = $notificationImageTable->newEntity();
            $notificationImageData = [
                'notification_id' => $lastInsertedId,
                'module_id' => $insertData['module_id'],
                'file_path' => $insertData['file_path'], 
            ];

            $notificationImage = $notificationImageTable->patchEntity($notificationImage, $notificationImageData);
            $notificationImageTable->save($notificationImage);
           
            $this->Flash->success(__('Data has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->Flash->error(__('Data could not be saved. Please, try again.'));
    }
    
    $clients = $this->hrisApiClients
        ->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])
        ->where(['status' => 1])
        ->enableHydration(false)
        ->toArray();

    $moduleArray = $this->moduleArray; 
    $uploadPath = $this->uploadPathView;
    $this->set(compact('notification', 'clients', 'moduleArray','uploadPath '));
}



    

public function edit($id = null)
{
    $notification = $this->ApiNotification->get($id);
    if ($this->request->is(['patch', 'post', 'put'])) {
        $employee_id = $this->Auth->user('emp_id');
        $getData = $this->request->getData();

        foreach ($getData as $key => $data) {
            if (!isset($getData[$key]['tmp_name'])) {
                $updateData[$key] = (is_array($data)) ? implode(",", $data) : $data;
            }
        }
        $updateData['module_id'] = $getData['module'];

        $updateData['updated_by'] = $employee_id;
        $updateData['updated_on'] = date('Y-m-d H:i:s');

        Male = $getData['default_male']['name'];
        if (!empty(Male)) {
            $attachment = $this->uploadAttachments($getData['default_male']);
            if (!empty($attachment)) {
                $updateData['default_male'] = $attachment;
            } else {
                $updateData['default_male'] = '';
            }
        }

        $defaultFemale = $getData['default_female']['name'];
        if (!empty($defaultFemale)) {
            $attachment = $this->uploadAttachments($getData['default_female']);
            if (!empty($attachment)) {
                $updateData['default_female'] = $attachment;
            } else {
                $updateData['default_female'] = '';
            }
        }

        $fileName = $getData['file_path']['name'];
        if (!empty($fileName)) {
            $attachment = $this->uploadAttachments($getData['file_path']);
            if (!empty($attachment)) {
                $updateData['file_path'] = $attachment;
            } else {
                $updateData['file_path'] = '';
            }
        }

        $notification = $this->ApiNotification->patchEntity($notification, $updateData);
        if ($this->ApiNotification->save($notification)) {
            $notificationImageTable = TableRegistry::get('hris_api_notification_image');
            $notificationImage = $notificationImageTable->find()
                ->where(['notification_id' => $id])
                ->first();

            if ($notificationImage) {
                $notificationImage->file_path = $updateData['file_path'];
                $notificationImageTable->save($notificationImage);
            } else {
                $notificationImage = $notificationImageTable->newEntity();
                $notificationImageData = [
                    'notification_id' => $id,
                    'module_id' => $updateData['module_id'],
                    'file_path' => $updateData['file_path'],
                ];
                $notificationImage = $notificationImageTable->patchEntity($notificationImage, $notificationImageData);
                $notificationImageTable->save($notificationImage);
            }

            $this->Flash->success(__('Data has been saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Data could not be saved..'));
    }

    $clients = $this->hrisApiClients
        ->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->where(['status' => 1])->enableHydration(false)->toArray();
    $uploadPath = $this->uploadPathView;
    $moduleArray = $this->moduleArray;
    $this->set(compact('clients', 'notification', 'uploadPath', 'moduleArray'));
}


    public function uploadAttachments($file){
        if (!$file) {
          return false;
        }

        if (isset($file['name']) && $file['name'] != "") {
            $uploadPath = $this->uploadPath; 
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777);
            }

          $allowed    =  array('png', 'jpg', 'jpeg');
          $filename   = $file['name'];
          $info       = pathinfo($filename);
          $ext        = pathinfo($filename, PATHINFO_EXTENSION);
         
          if (!in_array($ext, $allowed)) {
            $this->Flash->error('File Format Not Supported!');
            return false;
          }
          $fileName   = $info['filename'] . "_" . rand(12,time()) . "." . $info['extension'];
          $uploadFile = $uploadPath . $fileName;
          if (file_exists($uploadPath) && is_dir($uploadPath)) {
            move_uploaded_file($file['tmp_name'], $uploadFile);
            return  $fileName;
          } elseif (mkdir($uploadPath, 0888)) {
            move_uploaded_file($file['tmp_name'], $uploadFile);
            return  $fileName;
          }
          return false;
        }
        return false;
    }




public function rolecolormapping()
{
    $Notification = $this->ApiNotificationRoleColor->find('all')
      
        ->join([
            'hac' => [
                'table' => 'api_clients',
                'type' => 'LEFT',
            ],
        ])
        ->enableHydration(false)
        ->toArray();

    $this->set(compact('Notification'));
}

public function getClientRoles()
{
    $getData = $this->request->getData();
    $clients = $this->hrisApiClients
        ->find()
        ->where(['status' => 1, 'id' => $getData['client_id']])
        ->first();

    $this->changeDBConnection($clients);

    $rolesId = explode(",", $getData['role_id']);
    $htmlData = '';
    $where['status'] = 1;
    $roleData = TableRegistry::get('hris_roles')
        ->find('all')
        ->where($where)
        ->enableHydration(false)
        ->toArray();

    if (!empty($roleData)) {
        foreach ($roleData as $role) {
            $selected = (in_array($role['id'], $rolesId)) ? 'selected' : '';
            $htmlData .= '<option value="'.$role['id'].'" '.$selected.'>'.$role['title'].'</option>';
        }
    }
    echo $htmlData;
    exit;
}


    public function rolecolormappingadd(){
            $notification = $this->ApiNotificationRoleColor->newEntity();
            if ($this->request->is('post')) {
            $employee_id = $this->Auth->user('emp_id');
            $getData = $this->request->getData();
             
            $insertData = [];
            foreach ($getData as $key => $data) {
                $insertData[$key] = (is_array($data)) ? implode(',', $data) : $data;
            }
            $insertData['created_by'] = $employee_id;
            $insertData['created_on'] = date('Y-m-d H:i:s');
            
            $notification = $this->ApiNotificationRoleColor->patchEntity($notification, $insertData);
            if ($this->ApiNotificationRoleColor->save($notification)) {
                $this->Flash->success(__('Data has been saved.'));
                return $this->redirect(['action' => 'role-color-mapping']);
            }
            
            $this->Flash->error(__('Data could not be saved. Please, try again.'));
        }
        
        $clients = $this->hrisApiClients
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'name'
            ])
            ->where(['status' => 1])
            ->enableHydration(false)
            ->toArray();

        $moduleArray = $this->moduleArray; 

        $this->set(compact('notification', 'clients','moduleArray'));

         }

    public function rolecolormappingedit($id){
    $notification = $this->ApiNotificationRoleColor->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $employee_id             = $this->Auth->user('emp_id');
            $getData                  = $this->request->getData();

            foreach($getData as $key => $data){
                $updateData[$key] = (is_array($data))?implode(",", $data):$data;
            }

            $updateData['updated_by']     = $employee_id;
            $updateData['updated_on']     = date('Y-m-d H:i:s');

            $notification = $this->ApiNotificationRoleColor->patchEntity($notification, $updateData);
            if ($this->ApiNotificationRoleColor->save($notification)) {
                $this->Flash->success(__('Data has been saved.'));

                return $this->redirect(['action' => 'role-color-mapping']);
            }
            $this->Flash->error(__('Data could not be saved. Please, try again.'));
        }
        $clients = $this->hrisApiClients
                    ->find('list', [
                        'keyField' => 'id',
                        'valueField' =>'name'
                    ])->where(['status'=>1])->enableHydration(false)->toArray(); 
        $uploadPath = $this->uploadPathView;
        $this->set(compact('clients','notification','uploadPath'));


         }
 
        public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $notification = $this->ApiNotificationRoleColor->get($id);
        if ($this->ApiNotificationRoleColor->delete($notification)) {
            $this->Flash->success(__('Data has been deleted.'));
        } else {
            $this->Flash->error(__('Data could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'role-color-mapping']);
    }


    
    

    
    
}
