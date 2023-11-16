<?php

Class CobaltCMS {

  var $host;
  var $password;
  var $username;

  function get_hostname() {
    
    $hostname = getenv('HOSTNAME'); 
    if(!$hostname) { $hostname = trim(exec('hostname')); } 
    if(!$hostname) { $hostname = exec('echo $HOSTNAME'); }
    if(!$hostname) { $hostname = preg_replace('#^\w+\s+(\w+).*$#', '$1', exec('uname -a'));} 
    return $hostname;
  }

  function display_content() {
    $host = $this->get_hostname();
    $query_str = "SELECT * FROM cms_content ORDER BY created DESC LIMIT 3";
    $rows = mysql_query($query_str);

    if ( $rows !== false && mysql_num_rows($rows) > 0 ) {
      while ( $content = mysql_fetch_assoc($rows) ) {
        $title = stripslashes($content['title']);
        $bodytext = stripslashes($content['bodytext']);

        $entry .= <<<ENTRY
<h2>$title</h2>
<p>$bodytext</p>
ENTRY;

      }
    } else {

      $entry = <<<ENTRY
<h2>{$host} Is Under Construction</h2>
<p> No posts have been made on this page. Please check back soon, or click the link below to add an entry!</p>
<center><img src="construct2.gif"></center>
ENTRY;

    }

    $entry .= <<<ADMIN_LINK
<p class="admin_link"><a href="?admin=1">Add a New Entry</a></p>
ADMIN_LINK;

    return $entry;
  }

  function display_admin() {

    $admin_form = <<<ADMIN_FORM
    <p> Create a Post </p>
    <form action="{$_SERVER['PHP_SELF']}" method="post">
      <label for="title">Title:</label> <input name="title" id="title" type="text" maxlength="150" /><br />
      <label for="bodytext">Body Text:</label> <textarea name="bodytext" id="bodytext"></textarea><br />
      <input type="submit" value="Create Post!" />
    </form>
<p class="admin_link"><a href="?home=1">Go Back To The HomePage</a></p>
ADMIN_FORM;
    return $admin_form;
  }

  function write($post_content) {
    // mysql_real_escape_string() doesnt exist in PHP 4.0.4!!
    // FIXME: see https://www.php.net/manual/en/security.database.sql-injection.php

    if ( $post_content['title'] ) {
      // $title = mysql_real_escape_string($post_content['title']);
      $title = $post_content['title'];
    }
    if ( $post_content['bodytext']) {
      // $bodytext = mysql_real_escape_string($post_content['bodytext']);
      $bodytext = $post_content['bodytext'];
    }
    if ( $title && $bodytext ) {
      $created = time();
      $sql = "INSERT INTO cms_content VALUES('$title','$bodytext','$created')";

      return mysql_query($sql);
    } else {

      return false;
    }
  }


  function connect() {
    $dbh = mysql_connect($this->host,$this->username,$this->password) or die("Could not connect. " . mysql_error());
    mysql_select_db('cobaltnet',$dbh) or die("Could not select database. " . mysql_error());
    return $this->buildDB();
  }

  function buildDB() {
    $sql = <<<MySQL_QUERY
CREATE TABLE IF NOT EXISTS cms_content (
    title VARCHAR(150),
    bodytext  TEXT,
    created VARCHAR(100)
)
MySQL_QUERY;

    return mysql_query($sql);
  }
}
