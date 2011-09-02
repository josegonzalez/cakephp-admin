/*!
 * Bootstrap version 1
 *
 * Copyright 2011 Jose Diaz-Gonzalez
 * Licensed under the MIT License
 * http://www.opensource.org/licenses/mit-license.php
 */
/* Reset.less
 * Props to Eric Meyer (meyerweb.com) for his CSS reset file. We're using an adapted version here	that cuts out some of the reset
 * HTML elements we will never need here (i.e., dfn, samp, etc).
 * ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
html, body {
  margin: 0;
  padding: 0;
}
h1,
h2,
h3,
h4,
h5,
h6,
p,
blockquote,
pre,
a,
abbr,
acronym,
address,
cite,
code,
del,
dfn,
em,
img,
q,
s,
samp,
small,
strike,
strong,
sub,
sup,
tt,
var,
dd,
dl,
dt,
li,
ol,
ul,
fieldset,
form,
label,
legend,
button,
table,
caption,
tbody,
tfoot,
thead,
tr,
th,
td {
  margin: 0;
  padding: 0;
  border: 0;
  font-weight: normal;
  font-style: normal;
  font-size: 100%;
  line-height: 1;
  font-family: inherit;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}
ol, ul {
  list-style: none;
}
q:before,
q:after,
blockquote:before,
blockquote:after {
  content: "";
}
header,
section,
footer,
article,
aside {
  display: block;
}
/** General Style Info **/
body {
  background: #000000 url('../img/background.png');
  color: #fff;
  font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
  font-size: 90%;
  margin: 0;
}
a {
  color: #0686C8;
  text-decoration: underline;
  font-weight: bold;
}
a:hover {
  color: #056596;
  text-decoration: none;
}
a img {
  border: none;
}
h1,
h2,
h3,
h4 {
  font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
  font-weight: normal;
  margin-bottom: 0.5em;
  text-shadow: 0 1px 0 white;
}
h1 {
  color: #003d4c;
  font-size: 140%;
}
h2 {
  color: #000;
  border-bottom: 1px solid #ccc;
  font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
  font-size: 180%;
  padding-bottom: 5px;
}
h3 {
  color: #495961;
  font-family: "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
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
#header {
  padding: 16px 16px 0 16px;
}
#header h1 {
  color: #fff;
  float: left;
  height: 32px;
  line-height: 32px;
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
  margin: 16px;
  overflow: auto;
  -opera-border-radius: 5px;
  -o-border-radius: 5px;
  -khtml-border-radius: 5px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  -opera-border-radius: 0 2px 16px black;
  -o-border-radius: 0 2px 16px black;
  -khtml-border-radius: 0 2px 16px black;
  -moz-border-radius: 0 2px 16px black;
  -webkit-border-radius: 0 2px 16px black;
  border-radius: 0 2px 16px black;
}
#footer {
  clear: both;
  margin: 0 16px 16px 16px;
  text-align: right;
}
/** Containers **/
div.form, div.index, div.view {
  float: right;
  width: 76%;
  border-left: 1px solid #666;
  padding: 16px;
}
div.actions {
  float: left;
  width: 16%;
  padding: 16px;
}
div.actions h3 {
  color: #777;
  margin-bottom: 0.2em;
  margin-top: 1em;
  text-shadow: 0 1px 0 white;
}
div.actions h3:first-child {
  margin-top: 0;
}
/**  Actions  **/
div.actions ul {
  margin: 0;
  padding: 0;
}
div.actions li {
  margin: 0 0 0.5em 0;
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
  background: #FEFBF3;
  border: 1px solid rgba(0, 0, 0, 0.15);
  color: #000;
  display: block;
  font-family: Monaco, Andale Mono, Courier New, monospace;
  font-size: 12px;
  line-height: 16px;
  margin: 16px;
  padding: 16px;
  white-space: pre-wrap;
  -opera-border-radius: 3px;
  -o-border-radius: 3px;
  -khtml-border-radius: 3px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
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
  padding-left: 5px;
  display: block;
  margin-left: 10px;
}
div.code-coverage-results div.uncovered span.content {
  background: #ecc;
}
div.code-coverage-results div.covered span.content {
  background: #cec;
}
div.code-coverage-results div.ignored span.content {
  color: #aaa;
}
div.code-coverage-results span.line-num {
  color: #666;
  display: block;
  float: left;
  width: 20px;
  text-align: right;
  margin-right: 5px;
}
div.code-coverage-results span.line-num strong {
  color: #666;
}
div.code-coverage-results div.start {
  border: 1px solid #aaa;
  border-width: 1px 1px 0px 1px;
  margin-top: 30px;
  padding-top: 5px;
}
div.code-coverage-results div.end {
  border: 1px solid #aaa;
  border-width: 0px 1px 1px 1px;
  margin-bottom: 30px;
  padding-bottom: 5px;
}
div.code-coverage-results div.realstart {
  margin-top: 0px;
}
div.code-coverage-results p.note {
  color: #bbb;
  padding: 5px;
  margin: 5px 0 10px;
  font-size: 10px;
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
  -webkit-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: 0 2px 16px black, 0 0 1px black, 0 0 1px black, transparent 0 0 0, transparent 0 0 0;
  box-shadow: 0 2px 16px black;
  box-shadow: 0 2px 16px black 0 0 1px black 0 0 1px black transparent 0 0 0 transparent 0 0 0;
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
  text-decoration: underline;
}
.navigation li.first {
  margin-left: 0;
}
/** Tables **/
table {
  background: #fff;
  clear: both;
  color: #333;
  font-size: 11.2px;
  margin-bottom: 11.2px;
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
  border: 0;
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
  font-size: 11.2px;
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
  background: #fafafa;
  border-bottom: 1px solid #f0f0f0;
  line-height: 11.2px;
  padding: 6px;
  text-align: left;
  vertical-align: top;
}
table tr.altrow td {
  background: #fff;
}
table tr td a {
  height: 11.2px;
  line-height: 11.2px;
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
  -opera-border-top-right-radius: 5px;
  -o-border-top-right-radius: 5px;
  -khtml-border-top-right-radius: 5px;
  -moz-border-radius-topright: 5px;
  -webkit-border-top-right-radius: 5px;
  border-top-right-radius: 5px;
}
td.actions {
  text-align: center;
  white-space: nowrap;
}
table td.actions a {
  margin: 0px 6px;
  padding: 2px 5px;
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
  color: #fff;
}
/** Paging **/
p.paging-details {
  color: #666;
  font-size: 11.2px;
  padding-bottom: 11.2px;
  text-align: center;
}
div.paging {
  background: #F1F1F1;
  border: 1px solid #E5E5E5;
  clear: both;
  font-size: 11.2px;
  margin: 0 0 1em 0;
  padding: 11.2px;
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
dt {
  line-height: 2em;
  font-weight: bold;
  padding-left: 4px;
  vertical-align: top;
}
dd {
  line-height: 2em;
  margin-left: 10em;
  margin-top: -2em;
  vertical-align: top;
}
form {
  margin-bottom: 16px;
}
form fieldset {
  margin-bottom: 16px;
  padding-top: 16px;
}
form fieldset legend {
  display: block;
  margin-left: 150px;
  font-size: 20px;
  line-height: 1;
  color: #404040;
}
form fieldset p {
  color: #bfbfbf;
  display: block;
  font-size: 12px;
  line-height: 16px;
  margin-left: 150px;
  max-width: 600px;
}
form div.input {
  margin-bottom: 16px;
}
form .input.error {
  background-color: #FAE5E3;
  padding: 20px 0;
  -opera-border-radius: 4px;
  -o-border-radius: 4px;
  -khtml-border-radius: 4px;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
}
form .input.error .error-message {
  color: #9D261D;
  padding-left: 150px;
  padding-top: 20px;
}
form .input.error input[type=text], form .input.error input[type=password], form .input.error textarea {
  border-color: #C87872;
  -webkit-box-shadow: 0 0 3px rgba(171, 41, 32, 0.25), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: 0 0 3px rgba(171, 41, 32, 0.25), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: 0 0 3px rgba(171, 41, 32, 0.25), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: 0 0 3px rgba(171, 41, 32, 0.25), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: 0 0 3px rgba(171, 41, 32, 0.25), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: 0 0 3px rgba(171, 41, 32, 0.25), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: 0 0 3px rgba(171, 41, 32, 0.25);
  box-shadow: 0 0 3px rgba(171, 41, 32, 0.25) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
}
form .input.error input[type=text]:focus, form .input.error input[type=password]:focus, form .input.error textarea:focus {
  border-color: #ba554d;
  -webkit-box-shadow: 0 0 6px rgba(171, 41, 32, 0.5), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: 0 0 6px rgba(171, 41, 32, 0.5), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: 0 0 6px rgba(171, 41, 32, 0.5), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: 0 0 6px rgba(171, 41, 32, 0.5), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: 0 0 6px rgba(171, 41, 32, 0.5), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: 0 0 6px rgba(171, 41, 32, 0.5), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: 0 0 6px rgba(171, 41, 32, 0.5);
  box-shadow: 0 0 6px rgba(171, 41, 32, 0.5) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
}
form label,
form input,
form select,
form textarea {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 13px;
  font-weight: normal;
  line-height: normal;
}
form label {
  padding-top: 4px;
  margin-right: 20px;
  font-size: 13px;
  line-height: 18px;
  float: left;
  width: 130px;
  text-align: right;
  color: #404040;
}
form .checkbox label {
  padding-top: 0;
}
form input[type=checkbox], form input[type=radio] {
  cursor: pointer;
}
form input[type=text],
form input[type=password],
form textarea,
form select,
form .uneditable-input {
  display: inline-block;
  width: 530px;
  margin: 0;
  padding: 4px;
  font-size: 13px;
  line-height: 16px;
  height: 16px;
  color: #808080;
  border: 1px solid #ccc;
  -opera-border-radius: 3px;
  -o-border-radius: 3px;
  -khtml-border-radius: 3px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
}
form select, form input[type=file] {
  height: 24px;
  line-height: 24px;
}
form textarea {
  height: auto;
}
form :-moz-placeholder {
  color: #bfbfbf;
}
form ::-webkit-input-placeholder {
  color: #bfbfbf;
}
form input[type=text],
form input[type=password],
form select,
form textarea {
  -webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
  -moz-transition: border linear 0.2s, box-shadow linear 0.2s;
  transition: border linear 0.2s, box-shadow linear 0.2s;
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
}
form input[type=text]:focus, form input[type=password]:focus, form textarea:focus {
  outline: none;
  border-color: rgba(82, 168, 236, 0.8);
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
}
form textarea {
  overflow-y: scroll;
}
form input[readonly]:focus, form textarea[readonly]:focus, form input.disabled {
  background: #f5f5f5;
  border-color: #ddd;
  -webkit-box-shadow: none, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: none, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: none, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: none, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: none, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: none, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: none;
  box-shadow: none transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
}
input[type=submit] {
  width: auto;
}
.form .submit {
  margin-left: 150px;
}
.actions form fieldset {
  padding-top: 8px;
}
.actions form legend {
  margin-left: 0;
}
.actions form label {
  display: block;
  float: none;
  width: auto;
  font-weight: bold;
  text-align: left;
  line-height: 20px;
  padding-top: 0;
}
.actions form div.clearfix {
  margin-bottom: 8px;
}
.actions form div.clearfix div.input {
  margin-left: 0;
}
.actions form ul.inputs-list {
  margin-bottom: 0;
}
.actions form ul.inputs-list li {
  padding-top: 0;
}
.actions form ul.inputs-list li label {
  font-weight: normal;
  padding-top: 0;
}
.actions form input[type=text],
.actions form input[type=password],
.actions form textarea,
.actions form select,
.actions form .uneditable-input {
  width: auto;
}
/** Notices and Errors **/
.error-page #content {
  padding: 16px;
}
.error-page h2 {
  margin: 0 16px 16px 16px;
}
.error-page p, .flash {
  background-color: rgba(0, 0, 0, 0.15);
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(transparent), to(rgba(0, 0, 0, 0.15)));
  background-image: -moz-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
  background-image: -ms-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, transparent), color-stop(100%, rgba(0, 0, 0, 0.15)));
  background-image: -webkit-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
  background-image: -o-linear-gradient(transparent, rgba(0, 0, 0, 0.15));
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='transparent', endColorstr='rgba(0, 0, 0, 0.15)', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='transparent', endColorstr='rgba(0, 0, 0, 0.15)', GradientType=0);
  background-image: linear-gradient(transparent, rgba(0, 0, 0, 0.15));
  background-color: #e6e6e6;
  margin: 16px;
  padding: 8px 15px;
  color: #fff;
  text-shadow: none;
  border: 1px solid rgba(0, 0, 0, 0.25);
  -opera-border-radius: 4px;
  -o-border-radius: 4px;
  -khtml-border-radius: 4px;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.25);
}
.error-page p em, .flash em {
  color: #000;
}
.error-page p p, .flash p {
  color: #fff;
  margin-bottom: 0;
}
.error-page p p + p, .flash p + p {
  margin-top: 5px;
}
.error-page p p.notice, .flash p.notice {
  color: #000;
}
.error-page p p.notice em, .flash p.notice em {
  color: #333;
}
.error-page p.error, .flash.error {
  background-color: #c43c35;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#ee5f5b), to(#c43c35));
  background-image: -moz-linear-gradient(#ee5f5b, #c43c35);
  background-image: -ms-linear-gradient(#ee5f5b, #c43c35);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ee5f5b), color-stop(100%, #c43c35));
  background-image: -webkit-linear-gradient(#ee5f5b, #c43c35);
  background-image: -o-linear-gradient(#ee5f5b, #c43c35);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#ee5f5b', endColorstr='#c43c35', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ee5f5b', endColorstr='#c43c35', GradientType=0);
  background-image: linear-gradient(#ee5f5b, #c43c35);
  border-color: #c43c35 #c43c35 #882a25;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
}
.error-page p.notice, .flash.notice {
  background-color: #eedc94;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#fceec1), to(#eedc94));
  background-image: -moz-linear-gradient(#fceec1, #eedc94);
  background-image: -ms-linear-gradient(#fceec1, #eedc94);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fceec1), color-stop(100%, #eedc94));
  background-image: -webkit-linear-gradient(#fceec1, #eedc94);
  background-image: -o-linear-gradient(#fceec1, #eedc94);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#fceec1', endColorstr='#eedc94', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fceec1', endColorstr='#eedc94', GradientType=0);
  background-image: linear-gradient(#fceec1, #eedc94);
  border-color: #eedc94 #eedc94 #e4c652;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  color: #000;
}
.error-page p.success, .flash.success {
  background-color: #57a957;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#62c462), to(#57a957));
  background-image: -moz-linear-gradient(#62c462, #57a957);
  background-image: -ms-linear-gradient(#62c462, #57a957);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #62c462), color-stop(100%, #57a957));
  background-image: -webkit-linear-gradient(#62c462, #57a957);
  background-image: -o-linear-gradient(#62c462, #57a957);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#62c462', endColorstr='#57a957', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#62c462', endColorstr='#57a957', GradientType=0);
  background-image: linear-gradient(#62c462, #57a957);
  border-color: #57a957 #57a957 #3d773d;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
}
.error-page p.information, .flash.information {
  background-color: #339bb9;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#5bc0de), to(#339bb9));
  background-image: -moz-linear-gradient(#5bc0de, #339bb9);
  background-image: -ms-linear-gradient(#5bc0de, #339bb9);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #5bc0de), color-stop(100%, #339bb9));
  background-image: -webkit-linear-gradient(#5bc0de, #339bb9);
  background-image: -o-linear-gradient(#5bc0de, #339bb9);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#339bb9', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#5bc0de', endColorstr='#339bb9', GradientType=0);
  background-image: linear-gradient(#5bc0de, #339bb9);
  border-color: #339bb9 #339bb9 #22697d;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
}
.error-page p a.close, .flash a.close {
  float: right;
  margin-top: -2px;
  color: #fff;
  font-size: 20px;
  font-weight: bold;
  text-shadow: 0 1px 0 rgba(0, 0, 0, 0.5);
  filter: alpha(opacity=50);
  -khtml-opacity: 0.5;
  -moz-opacity: 0.5;
  opacity: 0.5;
  -opera-border-radius: 3px;
  -o-border-radius: 3px;
  -khtml-border-radius: 3px;
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
}
.error-page p a.close:hover, .flash a.close:hover {
  text-decoration: none;
  filter: alpha(opacity=50);
  -khtml-opacity: 0.5;
  -moz-opacity: 0.5;
  opacity: 0.5;
}
.destructive-form form {
  background: #f8dcda;
  border: 1px solid #f4c8c5;
  color: #404040;
  color: rgba(0, 0, 0, 0.8);
  margin-bottom: 16px;
  padding: 14px;
  text-shadow: 0 1px 0 rgba(255, 255, 255, 0.25);
  -opera-border-radius: 6px;
  -o-border-radius: 6px;
  -khtml-border-radius: 6px;
  -moz-border-radius: 6px;
  -webkit-border-radius: 6px;
  border-radius: 6px;
}
.destructive-form form p {
  color: #404040;
  color: rgba(0, 0, 0, 0.8);
  margin-right: 30px;
  margin-bottom: 0;
}
.destructive-form form ul {
  margin-bottom: 0;
}
.destructive-form form strong {
  display: block;
}
.destructive-form form a.close {
  display: block;
  color: #404040;
  color: rgba(0, 0, 0, 0.5);
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
}
.destructive-form form .submit {
  margin-top: 16px;
  margin-left: 0;
}
.destructive-form form .submit input[type="submit"] {
  font-size: 11px;
  margin-left: 0;
  padding: 0 9px;
}
input[type="submit"], div.actions ul li a, td.actions a {
  color: #333333;
  display: inline-block;
  background-color: #e6e6e6;
  background-repeat: no-repeat;
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), color-stop(0.25, #ffffff), to(#e6e6e6));
  background-image: -webkit-linear-gradient(#ffffff, color-stop(0.25, #ffffff), #e6e6e6);
  background-image: -moz-linear-gradient(#ffffff, #ffffff 25%, #e6e6e6);
  background-image: -ms-linear-gradient(#ffffff, color-stop(#ffffff, 0.25), #e6e6e6);
  background-image: -o-linear-gradient(#ffffff, color-stop(#ffffff, 0.25), #e6e6e6);
  background-image: linear-gradient(#ffffff, color-stop(#ffffff, 0.25), #e6e6e6);
  font-size: 12px;
  font-weight: normal;
  line-height: 13px;
  padding: 4px 14px;
  text-decoration: none;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-bottom-color: rgba(0, 0, 0, 0.25);
  -opera-border-radius: 4px;
  -o-border-radius: 4px;
  -khtml-border-radius: 4px;
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
  -webkit-transition: 0.1s linear all;
  -moz-transition: 0.1s linear all;
  transition: 0.1s linear all;
}
input[type="submit"]:hover, div.actions ul li a:hover, td.actions a:hover {
  background-position: 0 -15px;
  color: #333333;
  text-decoration: none;
}
input[type="submit"].primary, div.actions ul li a.primary, td.actions a.primary {
  background-color: #0064cd;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#049cdb), to(#0064cd));
  background-image: -moz-linear-gradient(#049cdb, #0064cd);
  background-image: -ms-linear-gradient(#049cdb, #0064cd);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #049cdb), color-stop(100%, #0064cd));
  background-image: -webkit-linear-gradient(#049cdb, #0064cd);
  background-image: -o-linear-gradient(#049cdb, #0064cd);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#049cdb', endColorstr='#0064cd', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#049cdb', endColorstr='#0064cd', GradientType=0);
  background-image: linear-gradient(#049cdb, #0064cd);
  color: #fff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
input[type="submit"].primary:hover, div.actions ul li a.primary:hover, td.actions a.primary:hover {
  color: #fff;
}
input[type="submit"].large, div.actions ul li a.large, td.actions a.large {
  font-size: 16px;
  line-height: 28px;
  -opera-border-radius: 6px;
  -o-border-radius: 6px;
  -khtml-border-radius: 6px;
  -moz-border-radius: 6px;
  -webkit-border-radius: 6px;
  border-radius: 6px;
}
input[type="submit"].small, div.actions ul li a.small, td.actions a.small {
  padding-right: 9px;
  padding-left: 9px;
  font-size: 11px;
}
input[type="submit"]:disabled,
div.actions ul li a:disabled,
td.actions a:disabled,
input[type="submit"].disabled,
div.actions ul li a.disabled,
td.actions a.disabled {
  background-image: none;
  filter: alpha(opacity=65);
  -khtml-opacity: 0.65;
  -moz-opacity: 0.65;
  opacity: 0.65;
  cursor: default;
}
input[type="submit"]:active, div.actions ul li a:active, td.actions a:active {
  -webkit-box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -opera-box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -o-box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -khtml-box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -moz-box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  -webkit-box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05), transparent 0 0 0, transparent 0 0 0, transparent 0 0 0, transparent 0 0 0;
  box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 3px 7px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.05) transparent 0 0 0 transparent 0 0 0 transparent 0 0 0 transparent 0 0 0;
}
input[type="submit"]::-moz-focus-inner, div.actions ul li a::-moz-focus-inner, td.actions a::-moz-focus-inner {
  padding: 0;
  border: 0;
}
td.actions a {
  font-size: 10px;
}
div.actions ul li a {
  text-align: center;
  width: 90%;
}
div.submit input[type="submit"] {
  background-color: #0064cd;
  background-repeat: repeat-x;
  background-image: -khtml-gradient(linear, left top, left bottom, from(#049cdb), to(#0064cd));
  background-image: -moz-linear-gradient(#049cdb, #0064cd);
  background-image: -ms-linear-gradient(#049cdb, #0064cd);
  background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #049cdb), color-stop(100%, #0064cd));
  background-image: -webkit-linear-gradient(#049cdb, #0064cd);
  background-image: -o-linear-gradient(#049cdb, #0064cd);
  -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#049cdb', endColorstr='#0064cd', GradientType=0)";
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#049cdb', endColorstr='#0064cd', GradientType=0);
  background-image: linear-gradient(#049cdb, #0064cd);
  color: #fff;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  font-size: 16px;
  line-height: 28px;
  -opera-border-radius: 6px;
  -o-border-radius: 6px;
  -khtml-border-radius: 6px;
  -moz-border-radius: 6px;
  -webkit-border-radius: 6px;
  border-radius: 6px;
}
div.submit input[type="submit"]:hover {
  color: #fff;
}
div.actions div.submit input[type="submit"] {
  padding: 0 9px;
  font-size: 11px;
}
