<section class="footer-info">
<div class="container">
    <div class="row">
        <div class="col-md-3 col-xs-6 col-content">
          <div id="progressData1" class="progressData"></div>
            <label>Familias felices</label>
        </div>
        <div class="col-md-3 col-xs-6 col-content">
          <div id="progressData2" class="progressData"></div>
          <label>Tazas de café</label>
        </div>
      <div class="col-md-3 col-xs-6 col-content">
        <div id="progressData3" class="progressData"></div>
        <label>días de Forfait</label>
      </div>
      <div class="col-md-3 col-xs-6 col-content">
          <div id="progressData4" class="progressData"></div>
          <label>noches en Apartamentos</label>
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

@media only screen and (max-width: 990px){
    .progressData{ margin-bottom: 20px; }
}
</style>