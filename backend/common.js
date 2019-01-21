var mysql = require('mysql');

var con = mysql.createConnection({
  host: "138.68.30.210",
  user: "manager",
  port: "3306".
  password: "g3t_t!cK37s",
  database: "zbt_tickets"
});

function sendQuery(query) {
  con.connect(function(err) {
    if (err) throw err;
    con.query(query, function (err, result, fields) {
      if (err) throw err;
      console.log(result);
    });
  });
}
