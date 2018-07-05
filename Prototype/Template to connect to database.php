//
//	Template to connect to First Chance Saloon database
//

<?php
$servername = "hive.csis.ul.ie";
$username = "Put username here";
$password = "Put password here";
$dbname = "Put database name here";


//$conn="";

$conn = new mysqli($servername, $username, $password,$dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 



// Show John's surname

$sql = "select surname from user_profile where first_name=\"John\";";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

print "<P>John's surname is {$row["surname"]}</P>";

// List all relationship types 

$sql = "select * from relationship_type;";
$result = $conn->query($sql);

print "<P>The following are the relationship types available to choose from in our dating web site</P>";
print "<table border=1><TR><TH>ID Number</TH><TH>word</TH></TR>\n";


while($row = $result->fetch_assoc()) 
  {
    // query variables that we used to get the ID numbers
    print "<TR><TD>{$row["id"]}</TD><TD>";
    print "{$row["relationship_type"]}</TD></TR>\n";
  }
print "</table>";


// List all genders 

$sql = "select * from gender;";
$result = $conn->query($sql);

print "<P>The following are the gender types available to choose from in our dating web site</P>";
print "<table border=1><TR><TH>ID Number</TH><TH>word</TH></TR>\n";


while($row = $result->fetch_assoc()) 
  {
    // query variables that we used to get the ID numbers
    print "<TR><TD>{$row["id"]}</TD><TD>";
    print "{$row["gender_name"]}</TD></TR>\n";
  }
print "</table>";


// List all interests 

$sql = "select * from interests;";
$result = $conn->query($sql);

print "<P>The following are the interests available to choose from in our dating web site</P>";
print "<table border=1><TR><TH>ID Number</TH><TH>word</TH></TR>\n";


while($row = $result->fetch_assoc()) 
  {
    // query variables that we used to get the ID numbers
    print "<TR><TD>{$row["interests_id"]}</TD><TD>";
    print "{$row["description"]}</TD></TR>\n";
  }
print "</table>";


// List all users emails

$sql = "select email from user_profile;";
$result = $conn->query($sql);

print "<P>The following the emails taken from user_profile</P>";
print "<table border=1>\n";
while($row = $result->fetch_assoc()) 
  {
    print "<TR><TD>{$row["email"]}</TD></TR>\n";
  }
print "</table>";

// List all encrypted black listed words 

$sql = "select * from black_list_word;";
$result = $conn->query($sql);

print "<P>The following are the encrypted black listed words</P>";
print "<table border=1><TR><TH>ID Number</TH><TH>word</TH></TR>\n";


while($row = $result->fetch_assoc()) 
  {
    // query variables that we used to get the ID numbers
    print "<TR><TD>{$row["id"]}</TD><TD>";
    print "{$row["word"]}</TD></TR>\n";
  }
print "</table>";





// What is average grade in CS4221?

//$sql = "select avg(grade) as res from grades where moduleCode=\"CS4221\";";
//$result = $conn->query($sql);
//$row = $result->fetch_assoc();

//print "<P>The average grade in CS4221 is {$row["res"]}</P>";

// What is highest/lowest grade in CS4086?

//$sql = "select max(grade) as highestGrade, min(grade) as lowestGrade from grades where moduleCode=\"CS4086\";";
//$result = $conn->query($sql);
//$row = $result->fetch_assoc();

//print "<P>The highest grade in CS4086 is {$row["highestGrade"]} and the lowest was {$row["lowestGrade"]}  </P>";





$conn->close();

?>
