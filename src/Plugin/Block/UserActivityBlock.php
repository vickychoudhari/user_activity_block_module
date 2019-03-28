<?php

namespace Drupal\user_activity_log\plugin\Block;

// use Drupal\Core\Form\FormInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
// use Drupal\Core\Form\FormBase;
/**
   * Provides a 'user' block.
   *
   * @Block(
   *  id = "user_block",
   *  admin_label = @Translation("THE USER INFO  "),
   * )
   */

class UserActivityBlock extends BlockBase {

/**
 * User Activity Log block.
 */
  public function build() {

 /**
 * Total node created by user.
 */
   $query = \Drupal::database()->select('node', 'n');
   $query->addTag('node_access');
   $query->addExpression('COUNT(*)');
   $result1 = $query->execute()->fetchField();
   // echo '<pre>';
   // print_r($result1);
   // die();

   

/**
 * Total comments by user.
 */
   $query = \Drupal::database()->select('comment', 'c');
   $query->addTag('node_access');
   $query->addExpression('COUNT(*)');
   $result2 =$query->execute()->fetchField();
     // echo '<pre>';
     // print_r($result2);
     // die();


/**
 * Latest node created by user.
 */
 
   $query = \Drupal::database()->select('node_field_data', 'n');
   $query->Join('users_field_data', 'u', 'n.uid = u.uid');
   $query->fields('n', array('title', 'nid'));
   $query->fields('u', array('uid')); 
   $query->orderBy('n.created', 'DESC');
   $query->range(0, 3);

  
$result = $query->execute()->fetchAll();
// echo '<pre>';
// print_r($result);
// die();
$list_of_nodes =[];
foreach ($result as $key => $value) {
$options = ['absolute' => true];
$url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' =>$value->nid ], $options);
$path_list= $url->toString();
$list_of_nodes[$key]['title'] = $value->title;
$list_of_nodes[$key]['url'] = $path_list;

}
// echo "<pre>";
// print_r($list_of_nodes);
// die();


/**
 * Latest comment by user.
 */

$query = \Drupal::database()->select('comment_field_data', 'c');
$query->join('node_field_data', 'n', 'c.uid = n.uid');
$query->fields('c', array('subject', 'uid'));
$query->fields('n', array('nid'));
$query->orderBy('n.created', 'DESC');
$query->range(0, 3);
$result = $query->execute()->fetchAll();
// echo "<pre>";
// print_r($count4);
// die();
$list_of_created_nodes =[];
foreach ($result as $key => $value) {
  $options = ['absolute' => true];
$url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' =>$value->nid ], $options);
$path_list= $url->toString();
$list_of_created_nodes[$key]['subject'] = $value->subject;
$list_of_created_nodes[$key]['uri'] = $path_list;

  
}
// echo "<pre>";
// print_r($list_of_created_nodes);
// die();







    

   return array(
      '#theme' => 'user_activity_log_tpl',
      '#nodes_build' => $result1,
      '#comment_build' => $result2,
      '#list_nodes_build' => $list_of_nodes,
      '#list_comment_build' => $list_of_created_nodes,
      '#cache' => ['max-age' => 0,
          ],
      
    );
  }
}