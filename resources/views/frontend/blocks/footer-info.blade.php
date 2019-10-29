<section class="footer-info">
<div class="container">
    <div class="row">
        <div class="col-md-3 col-xs-6 col-content">
          <label>Familias felices</label>
          <div id="progressData1" class="progressData"></div>
        </div>
        <div class="col-md-3 col-xs-6 col-content">
          <label>Tazas de café</label>
          <div id="progressData2" class="progressData"></div>
        </div>
      <div class="col-md-3 col-xs-6 col-content">
        <label>días de Forfait</label>
        <div id="progressData3" class="progressData"></div>
      </div>
      <div class="col-md-3 col-xs-6 col-content">
        <label>noches en Apartamentos</label>
        <div id="progressData4" class="progressData"></div>
      </div>
    </div>
</div>
</section>

<style>
  
  .col-content{
    min-height: 18em;
  }
.footer-info label {
    color: #3F51B5 !important;
    margin-top: 1em;
    width: 100%;
    text-align: center;
    font-size: 1.4em;
    text-shadow: 1px 1px 0px #badeff;
    white-space: nowrap;
}
.progressData {
    max-width: 150px;
    margin: auto;
}
 
.progressData .number{
    font-size: 28px;
    line-height: 0;
    text-shadow: 1px 1px 0px #36495b;
    color: #000;
}

.progress.blue .progress-bar{
    border-color: #3F51B5;
}
/*.footer-area{
    background: url(/img/miramarski/contacto.jpg) center;
}*/

@media only screen and (max-width: 990px){
    .progressData{ margin-bottom: 20px; }
}
/*@media only screen and (max-width: 768px){
  .footer-info label {
    color: #e2e2e4 !important;
  }
}*/
@media only screen and (max-width: 425px){
  .col-xs-6.col-content {
        width: 90%;
  }
}
</style>