<?php /**
 * @file
 * Contains \Drupal\sbhs_past_workshop\Controller\DefaultController.
 */

namespace Drupal\sbhs_past_workshop\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Database\Database;
use Drupal\Core\Render\Markup;
/**
 * Default controller for the sbhs_past_workshop module.
 */
class DefaultController extends ControllerBase {

  // public function sbhs_past_workshop_view_all($workshop_id = 0) {

  //   $page_content = "";
  //   $query = "
  //       SELECT * FROM sbhs_workshop 
  //       ORDER BY date desc";
  //   $result = \Drupal::database()->query($query);
  //   $headers = ["Date", "Institute", "City", "No of Students", "Action"];
  //   $rows = [];
  //   $options = ['attributes' => ['class' => 'delete']];
  //   // while ($row = db_fetch_object($result)) {
  //   //   $item = [
  //   //     $row->date,
  //   //     $row->institute,
  //   //     $row->city,
  //   //     $row->no_of_students,
  //   //     // l("view", "workshops/view_details/{$row->w_id}"),
  //   //   ];
  //   //   array_push($rows, $item);
  //   // }
  //   while ($row = $query->fetchObject()) {
  //     $link = Link::fromTextAndUrl('view', Url::fromRoute('workshops.view_details', ['w_id' => $row->w_id]))->toString();
  //     $item = [
  //       $row->date,
  //       $row->institute,
  //       $row->city,
  //       $row->no_of_students,
  //       $link,
  //     ];
  //     $items[] = $item;
  //   }
  //   $page_content .= theme("table", $headers, $rows);

  //   return $page_content;
  // }
  public function sbhs_past_workshop_view_all($workshop_id = 0) {
    $headers = ["Date", "Institute", "City", "No of Students", "Action"];
    $rows = [];
  
    // Query the database to get workshop data.
    $result = \Drupal::database()->query("SELECT * FROM sbhs_workshop ORDER BY date DESC");
  
    // Process the query results.
    foreach ($result as $row) {
      // Create a link using Drupal's Link and Url classes.
      $link = Link::fromTextAndUrl('view', Url::fromRoute('sbhs_past_workshop.view_details_all', ['w_id' => $row->w_id]))
        ->toString();
  
      // Add each row of data to the $rows array.
      $rows[] = [
        'date' => $row->date,
        'institute' => $row->institute,
        'city' => $row->city,
        'no_of_students' => $row->no_of_students,
        'action' => $link,
      ];
    }
  
    // Create a render array for the table.
    $table = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#attributes' => ['class' => ['workshop-table']],
    ];
  
    // Return the render array to be displayed on the page.
    return [
      '#markup' => render($table),
    ];
  }
  
  

  public function sbhs_past_workshop_display_all() {
    $page_content = "";
    $page_content .= \Drupal::formBuilder()->getForm("sbhs_past_workshop_add_form");
    return $page_content;

  }

  public function sbhs_past_workshop_edit_all($workshop_id = 0) {
    $page_content = "";
    if ($workshop_id) {
      $page_content .= \Drupal::formBuilder()->getForm("sbhs_past_workshop_add_form", $workshop_id);
    }
    else {
      $query = "
            SELECT * FROM sbhs_workshop 
            ORDER BY date desc";
      $result = \Drupal::database()->query($query);
      $headers = ["Date", "Institute", "City", "No of Students", "Action"];
      $rows = [];
      $options = ['attributes' => ['class' => 'delete']];
      while ($row = db_fetch_object($result)) {
        $item = [
          $row->date,
          $row->institute,
          $row->city,
          $row->no_of_students,
          // l("Edit", "workshops/edit/{$row->w_id}") . " | " . l("Delete", "workshops/delete/{$row->w_id}", $options),
        ];
        array_push($rows, $item);
      }
      $page_content .= theme("table", $headers, $rows);
    }
    return $page_content;
  }

  public function sbhs_past_workshop_delete_all($workshop_id = 0) {
    $page_content = "";
    $query = "DELETE from sbhs_workshop 
        WHERE w_id = {$workshop_id}";
    $result = \Drupal::database()->query($query, $workshop_id);
    if (!$result) {
      \Drupal::messenger()->addMessage("Something went wrong, please try again.", "error");
    }
    else {
      \Drupal::messenger()->addMessage("Workshop Deleted successfully", "status");
    }
    return $page_content;
  }

  // public function sbhs_past_workshop_view_details_all($workshop_id = 0) {
  //   $page_content = "";
  //   $query = "
  //       SELECT * FROM sbhs_workshop 
  //       ORDER BY date desc";
  //   $result = \Drupal::database()->query($query);
  //   $row = db_fetch_object($result);
  //   $page_content .= "<table class='workshop-details'>";
  //   $page_content .= "<tr><td><b>Institute</b></td><td>{$row->institute}</td></tr>";
  //   $page_content .= "<tr><td><b>Workshop Date</b></td><td>{$row->date}</td></tr>";
  //   $page_content .= "<tr><td><b>Workshop Time</b></td><td>{$row->time}</td></tr>";
  //   $page_content .= "<tr><td><b>No. of Participants</b></td><td>{$row->no_of_students}</td></tr>";
  //   $page_content .= "<tr><td><b>Organiser</b></td><td>{$row->organiser}</td></tr>";
  //   $page_content .= "<tr><td><b>Street</b></td><td>{$row->street}</td></tr>";
  //   $page_content .= "<tr><td><b>City</b></td><td>{$row->city}</td></tr>";
  //   $page_content .= "<tr><td><b>State</b></td><td>{$row->state}</td></tr>";
  //   $page_content .= "<tr><td><b>Pincode</b></td><td>{$row->pincode}</td></tr>";
  //   $page_content .= "<tr><td><b>Description</b></td><td>{$row->body}</td></tr>";
  //   $page_content .= "</table>";


  //   return $page_content;

  // }
  public function sbhs_past_workshop_view_details_all($workshop_id = 0) {
    $connection = Database::getConnection();
  
    // Fetch the workshop details.
    $query = $connection->select('sbhs_workshop', 'w')
      ->fields('w', [
        'institute',
        'date',
        'time',
        'no_of_students',
        'organiser',
        'street',
        'city',
        'state',
        'pincode',
        'body',
      ])
      ->condition('w_id', $workshop_id)
      ->execute();
  
    $row = $query->fetchObject();
  
    // If no row is found, return a message.
    if (!$row) {
      return [
        '#markup' => $this->t('No workshop details found for the given ID.'),
      ];
    }
  
    // Create a render array for the table.
    $table = [
      '#type' => 'table',
      '#attributes' => ['class' => ['workshop-details']],
      '#rows' => [
        ['Institute', $row->institute],
        ['Workshop Date', $row->date],
        ['Workshop Time', $row->time],
        ['No. of Participants', $row->no_of_students],
        ['Organiser', $row->organiser],
        ['Street', $row->street],
        ['City', $row->city],
        ['State', $row->state],
        ['Pincode', $row->pincode],
        ['Description', Markup::create($row->body)], // Use Markup for HTML-safe output.
      ],
    ];
  
    // Return the render array to Drupal for rendering.
    return $table;
  }
  
  
  public function sbhs_past_workshop_view_upcoming_all() {
    $page_content = "";
    $page_content .= "<br/><h5>There are no upcoming workshops for now. Keep checking for updates</h5>";
    return $page_content;

  }

}
