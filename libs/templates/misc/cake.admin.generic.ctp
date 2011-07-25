/**
 *
 * Generic CSS for CakeAdmin
 * Modified from Generic CSS for CakePHP
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		  http://cakephp.org CakePHP(tm) Project
 * @package	   cake
 * @subpackage	cake.app.webroot.css
 * @since		 CakePHP(tm)
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

* {
	margin:0;
	padding:0;
}

/** General Style Info **/
body {
	background: #000 url('../img/background.png');
	color: #fff;
	font-family:'lucida grande',verdana,helvetica,arial,sans-serif;
	font-size:90%;
	margin: 0;
}
a {
	color: #0686C8;
	text-decoration: underline;
	font-weight: bold;
}
a:hover {
	color: #056596;
	text-decoration:none;
}
a img {
	border:none;
}
h1, h2, h3, h4 {
	font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
	font-weight: normal;
	margin-bottom:0.5em;
	text-shadow: 0 1px 0 white;
}
h1 {
	color: #003d4c;
	font-size: 140%;
}
h2 {
	color: #000;
	font-family:'Gill Sans','lucida grande', helvetica, arial, sans-serif;
	font-size: 180%;
}
h3 {
	color: #495961;
	font-family:'Gill Sans','lucida grande', helvetica, arial, sans-serif;
	font-size: 155%;
}
h4 {
	color: #111;
	font-size: 12px;
	font-weight: bold;
}
ul, li {
	margin: 0 12px;
}

/** Layout **/
#container {
	text-align: left;
}

#header{
	padding: 20px 20px 0 20px;
}
#header h1 {
	color: #fff;
	float: left;
	height: 36px;
	line-height: 20px;
	padding: 0;
	text-shadow: 2px 2px 2px #000;
}
#header h1 a {
	color: #fff;
	background: #003d4c;
	font-weight: normal;
	text-decoration: none;
}
#header h1 a:hover {
	color: #fff;
	background: #003d4c;
	text-decoration: underline;
}
#content {
	background: #f9f9f9 url('../img/background-noise.gif');
	clear: both;
	color: #333;
	margin: 10px 20px 20px 20px;
	overflow: auto;
	-opera-border-radius: 5px;
	-o-border-radius: 5px;
	-khtml-border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	-opera-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	-o-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	-khtml-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	-moz-box-shadow: 0 2px 16px black, 0 0 1px
	-webkit-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
}
#footer {
	clear: both;
	margin: 0 20px 20px 20px;;
	text-align: right;
}

/** Containers **/
div.form,
div.index,
div.view {
	float:right;
	width:76%;
	border-left:1px solid #666;
	padding:10px 2%;
}
div.actions {
	float:left;
	width:16%;
	padding:10px 1.5%;
}
div.actions h3 {
	color:#777;
	margin-bottom: 0.2em;
	margin-top: 1em;
	text-shadow: 0 1px 0 white;
}
div.actions h3:first-child {
	margin-top: 0;
}

/** Navigation **/
.navigation {
	background-color: rgba(200, 200, 200, 0.1);
	border: 1px solid #444;
	float: right;
	height: 28px;
	margin: 0;
	padding: 0;
	-opera-border-radius: 2px;
	-o-border-radius: 2px;
	-khtml-border-radius: 2px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	border-radius: 2px;
	-opera-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	-o-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	-khtml-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	-moz-box-shadow: 0 2px 16px black, 0 0 1px
	-webkit-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
	box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black;
}
.navigation li {
	color: #fff;
	list-style: none;
	float: left;
	margin: 0 2px;
}
.navigation li a {
	color: #fff;
	display: block;
	font-size: 12px;
	font-weight: normal;
	line-height: 16px;
	padding: 6px 1em;
	text-decoration: none;
	text-shadow: 2px 2px 2px black;
}
.navigation li a:hover {
	text-decoration:underline;
}
.navigation li.first {
	margin-left: 0;
}

/** Tables **/
table {
	background: #fff;
	clear: both;
	color: #333;
	font-size: 10px;
	margin-bottom: 10px;
	width: 100%;
}
table a {
	color: #1B252C;
	text-decoration: none;
}
table a:hover {
	color: #1B252C;
	text-decoration: underline;
}
thead tr th:first-child {
	border-left: 1px solid #ddd;
	-opera-border-top-left-radius: 5px;
	-o-border-top-left-radius: 5px;
	-khtml-border-top-left-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-webkit-border-top-left-radius: 5px;
	border-top-left-radius: 5px;	
}
thead tr th:last-child {
	-opera-border-top-right-radius: 5px;
	-o-border-top-right-radius: 5px;
	-khtml-border-top-right-radius: 5px;
	-moz-border-radius-topright: 5px;
	-webkit-border-top-right-radius: 5px;
	border-top-right-radius: 5px;
}
th {
	background-color: #f5f5f5;
	border:0;
	border-bottom: 1px solid #ddd;
	border-top: 1px solid #fdfdfd;
	text-align: left;
	padding: 4px;
}
th.actions {
	text-align: center;
}
th a {
	display: block;
	font-size: 10px;
	font-weight: normal;
	padding: 2px 4px;
}
th a.asc:after {
	content: ' ⇣';
}
th a.desc:after {
	content: ' ⇡';
}
table tr td {
	background: #fff;
	border-bottom: 1px solid #f0f0f0;
	line-height: 13px;
	padding: 6px;
	text-align: left;
	vertical-align: top;
}
table tr td a {
	height: 13px;
	line-height: 13px;
	padding: 2px 0;
}
table tr td:first-child {
	border-left: 1px solid #f0f0f0;
}
table tr td:last-child {
	border-right: 1px solid #f0f0f0;
}
table tr:last-child td:first-child {
	-opera-border-bottom-left-radius: 5px;
	-o-border-bottom-left-radius: 5px;
	-khtml-border-bottom-left-radius: 5px;
	-moz-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
	border-bottom-left-radius: 5px;
}
table tr:last-child td:last-child {
	-opera-border-bottom-right-radius: 5px;
	-o-border-bottom-right-radius: 5px;
	-khtml-border-bottom-right-radius: 5px;
	-moz-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	border-bottom-right-radius: 5px;
}
td.actions {
	text-align: center;
	white-space: nowrap;
}
table td.actions a {
	margin: 0px 6px;
	padding:2px 5px;
}
.cake-sql-log table {
	background: #f4f4f4;
}
.cake-sql-log td {
	padding: 4px 8px;
	text-align: left;
	font-family: Monaco, Consolas, "Courier New", monospaced;
}
.cake-sql-log caption {
	color:#fff;
}

/** Paging **/
p.paging-details {
	color: #666;
	font-size: 10px;
	padding-bottom: 10px;
	text-align: center;
}
div.paging {
	background: #F1F1F1;
	border: 1px solid #E5E5E5;
	clear:both;
	font-size: 10px;
	margin: 0 0 1em 0;
	padding: 1em;
	text-align: center;
	-opera-border-radius: 5px;
	-o-border-radius: 5px;
	-khtml-border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}
div.paging span {
	padding: .2em .3em;
}
div.paging span.disabled {
	color: #ddd;
	display: inline;
}
div.paging span.current {
	color: #D1485E;
	font-weight: bold;
}
div.paging span a {
	color: #111;
	font-weight: normal;
	text-decoration: none;
}
div.paging span a:hover {
	text-decoration: underline;
}

/** Scaffold View **/
dl {
	line-height: 2em;
	margin: 0em 0em;
	width: 60%;
}
dl .altrow {
	background: #f4f4f4;
}
dt {
	font-weight: bold;
	padding-left: 4px;
	vertical-align: top;
}
dd {
	margin-left: 10em;
	margin-top: -2em;
	vertical-align: top;
}

/** Forms **/
form {
	clear: both;
	margin-right: 20px;
	padding: 0;
	width: 95%;
}
fieldset {
	border: 1px solid #ccc;
	margin-bottom: 1em;
	padding: 16px 20px;
}
fieldset legend {
	background:#fff;
	color: #e32;
	font-size: 160%;
	font-weight: bold;
}
fieldset fieldset {
	margin-top: 0px;
	margin-bottom: 20px;
	padding: 16px 10px;
}
fieldset fieldset legend {
	font-size: 120%;
	font-weight: normal;
}
fieldset fieldset div {
	clear: left;
	margin: 0 20px;
}
form div {
	clear: both;
	margin-bottom: 1em;
	padding: .5em;
	vertical-align: text-top;
}
form .input {
	color: #444;
}
form .required {
	font-weight: bold;
}
form .required label:after {
	color: #e32;
	content: '*';
	display:inline;
}
form div.submit {
	border: 0;
	clear: both;
	margin-top: 10px;
}
label {
	color: #111;
	display: block;
	font-size: 12px;
	font-weight: bold;
	margin-bottom:3px;
}
input, textarea {
	clear: both;
	font-size: 140%;
	font-family: "frutiger linotype", "lucida grande", "verdana", sans-serif;
	padding: 1%;
	width:98%;
}
select {
	clear: both;
	font-size: 120%;
	vertical-align: text-bottom;
}
select[multiple=multiple] {
	width: 100%;
}
option {
	font-size: 120%;
	padding: 0 3px;
}
input[type=checkbox] {
	clear: left;
	float: left;
	margin: 0px 6px 7px 2px;
	width: auto;
}
div.checkbox label {
	display: inline;
}
input[type=radio] {
	float:left;
	width:auto;
	margin: 0 3px 7px 0;
}
div.radio label {
	margin: 0 0 6px 20px;
}
input[type=submit] {
	display: inline;
	font-size: 110%;
	width: auto;
}
form .submit input[type=submit] {
	background: -webkit-gradient(linear, left top, left bottom, from(#00adee), to(#0078a5));
	background-image: -moz-linear-gradient(top, #00adee, #0078a5);
	border-color: #2d6324;
	color: #d9eef7;
	text-shadow: none;
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#00adee', endColorstr='#0078a5');
	-opera-border-radius: 0.2em;
	-o-border-radius: 0.2em;
	-khtml-border-radius: 0.2em;
	-moz-border-radius: 0.2em;
	-webkit-border-radius: 0.2em;
	border-radius: 0.2em;
}
form .submit input[type=submit]:hover {
	background:#4ca83d;
	background: -webkit-gradient(linear, left top, left bottom, from(#0095cc), to(#00678e));
	background-image: -moz-linear-gradient(top, #0095cc, #00678e);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#0095cc', endColorstr='#00678e');
}
form .submit input[type=submit]:active {
  color: #80bed6;
	background: -webkit-gradient(linear, left top, left bottom, from(#0078a5), to(#00adee));
	background: -moz-linear-gradient(top,  #0078a5,  #00adee);
	filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#0078a5', endColorstr='#00adee');
}

/** Notices and Errors **/
div.flash {
	clear: both;
	color: #fff;
	font-size: 120%;
	line-height: 140%;
	margin: 1em 0;
	padding: 0.8em;
}
div.flash em {
	color: #000;
	font-weight: normal;
	line-height: 140%;
}
div.error {
	background-color: #c73e14;
	font-family: Courier, monospace;
}
div.information {
	background-color:#50b0ec;
}
div.notice {
	background-color: #ffcc00;
	color: #000;
	font-family: Courier, monospace;
}
div.success {
	background-color: green;
}
div#flashMessage.message {
  clear: both;
  color: white;
  font-size: 120%;
  line-height: 140%;
  margin: 1em 0;
  padding: 0.8em;
  background-color: green;
}

/**  Actions  **/
div.actions ul {
	margin: 0;
	padding: 0;
}
div.actions li {
	margin:0 0 0.5em 0;
	list-style-type: none;
	white-space: nowrap;
	padding: 0;
}
div.actions ul li a {
	font-weight: normal;
	display: block;
	clear: both;
}
div.actions ul li a:hover {
	text-decoration: underline;
}

/** Related **/
div.related {
	clear: both;
	display: block;
}

/** Debugging **/
pre {
	color: #000;
	background: #f0f0f0;
	padding: 1em;
}
pre.cake-debug {
	background: #ffcc00;
	font-size: 120%;
	line-height: 140%;
	margin-top: 1em;
	overflow: auto;
	position: relative;
}
div.cake-stack-trace {
	background: #fff;
	color: #333;
	margin: 0px;
	padding: 6px;
	font-size: 120%;
	line-height: 140%;
	overflow: auto;
	position: relative;
}
div.cake-code-dump pre {
	position: relative;
	overflow: auto;
}
div.cake-stack-trace pre, div.cake-code-dump pre {
	color: #000;
	background-color: #F0F0F0;
	margin: 0px;
	padding: 1em;
	overflow: auto;
}
div.cake-code-dump pre, div.cake-code-dump pre code {
	clear: both;
	font-size: 12px;
	line-height: 15px;
	margin: 4px 2px;
	padding: 4px;
	overflow: auto;
}
div.cake-code-dump span.code-highlight {
	background-color: #ff0;
	padding: 4px;
}
div.code-coverage-results div.code-line {
	padding-left:5px;
	display:block;
	margin-left:10px;
}
div.code-coverage-results div.uncovered span.content {
	background:#ecc;
}
div.code-coverage-results div.covered span.content {
	background:#cec;
}
div.code-coverage-results div.ignored span.content {
	color:#aaa;
}
div.code-coverage-results span.line-num {
	color:#666;
	display:block;
	float:left;
	width:20px;
	text-align:right;
	margin-right:5px;
}
div.code-coverage-results span.line-num strong {
	color:#666;
}
div.code-coverage-results div.start {
	border:1px solid #aaa;
	border-width:1px 1px 0px 1px;
	margin-top:30px;
	padding-top:5px;
}
div.code-coverage-results div.end {
	border:1px solid #aaa;
	border-width:0px 1px 1px 1px;
	margin-bottom:30px;
	padding-bottom:5px;
}
div.code-coverage-results div.realstart {
	margin-top:0px;
}
div.code-coverage-results p.note {
	color:#bbb;
	padding:5px;
	margin:5px 0 10px;
	font-size:10px;
}
div.code-coverage-results span.result-bad {
	color: #a00;
}
div.code-coverage-results span.result-ok {
	color: #fa0;
}
div.code-coverage-results span.result-good {
	color: #0a0;
}
/* ------------------------------------------
CSS3 GITHUB BUTTONS (Nicolas Gallagher)
Licensed under Unlicense
http://github.com/necolas/css3-github-buttons
------------------------------------------ */
/* ------------------------------------------------------------------------------------------------------------- BUTTON */
input[type="submit"], div.actions ul li a, td.actions a {
	position: relative;
	overflow: visible;
	display: inline-block;
	padding: 0.5em 1em;
	border: 1px solid #d4d4d4;
	margin: 0;
	text-decoration: none;
	text-shadow: 1px 1px 0 #fff;
	font:11px/normal sans-serif;
	color: #333;
	white-space: nowrap;
	cursor: pointer;
	outline: none;
	background-color: #ececec;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f4f4f4), to(#ececec));
	background-image: -moz-linear-gradient(#f4f4f4, #ececec);
	background-image: -o-linear-gradient(#f4f4f4, #ececec);
	background-image: linear-gradient(#f4f4f4, #ececec);
	-webkit-background-clip: padding;
	-moz-background-clip: padding;
	-o-background-clip: padding-box;
	/*background-clip: padding-box;*/ /* commented out due to Opera 11.10 bug */
	-opera-border-radius: 0.2em;
	-o-border-radius: 0.2em;
	-khtml-border-radius: 0.2em;
	-moz-border-radius: 0.2em;
	-webkit-border-radius: 0.2em;
	border-radius: 0.2em;
	/* IE hacks */
	zoom: 1;
	*display: inline;
}
div.actions ul li a {
	-opera-border-radius: 50em;
	-o-border-radius: 50em;
	-khtml-border-radius: 50em;
	-moz-border-radius: 50em;
	-webkit-border-radius: 50em;
	border-radius: 50em;
	width: 90%;
}
input[type=submit]:hover, input[type=submit]:focus, input[type=submit]:active, input[type=submit].active,
div.actions ul li a:hover, div.actions ul li a:focus, div.actions ul li a:active, div.actions ul li a.active,
td.actions a:hover, td.actions a:focus, td.actions a:active, td.actions a.active {
	border-color: #3072b3;
	border-bottom-color: #2a65a0;
	text-decoration: none;
	text-shadow: -1px -1px 0 rgba(0,0,0,0.3);
	color: #fff;
	background-color: #3C8DDE;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#599bdc), to(#3072b3));
	background-image: -moz-linear-gradient(#599bdc, #3072b3);
	background-image: -o-linear-gradient(#599bdc, #3072b3);
	background-image: linear-gradient(#599bdc, #3072b3);
}
input[type=submit]:active, input[type=submit].active,
div.actions ul li a:active, div.actions ul li a.active,
td.actions a:active, td.actions a.active {
	border-color: #2a65a0;
	border-bottom-color: #3884CF;
	background-color: #3072b3;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#3072b3), to(#599bdc));
	background-image: -moz-linear-gradient(#3072b3, #599bdc);
	background-image: -o-linear-gradient(#3072b3, #599bdc);
	background-image: linear-gradient(#3072b3, #599bdc);
}

/* overrides extra padding on button elements in Firefox */
input[type=submit]::-moz-focus-inner, div.actions ul li a::-moz-focus-inner, td.actions a::-moz-focus-inner {
	padding: 0;
	border: 0;
}