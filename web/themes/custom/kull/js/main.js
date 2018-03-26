(function() {
  try {  
    document.createEvent("TouchEvent");
    hasTouch = true;
  }
  catch (e) {
    hasTouch = false;
  }
  if (!hasTouch) {
    document.body.className += ' no-touch';
  }
  else {
    document.body.className += ' has-touch';
  }

  document.querySelector('.mobile-menu-button').addEventListener("click", function(){
    this.classList.toggle("open");
    document.querySelector('.js-overlay').classList.toggle("visible");
    document.querySelector('.main-menu').classList.toggle("open");
  }, false);
  
  var linksLevel1 = document.querySelectorAll('.has-touch .main-menu--level-1.main-menu--links .multiple-links>a');
  
  for (var i=0; i< linksLevel1.length; i++) {
    linksLevel1[i].addEventListener("click", function(event){
      event.preventDefault();
      this.parentElement.classList.toggle("open");
      var secondLevel = this.parentElement.querySelector('.main-menu--level-2');
      var sizeSecondLevel = secondLevel.childElementCount * 63;
      secondLevel.style.height = sizeSecondLevel + "px";
      if (!this.parentElement.classList.contains('open')) {
        this.blur();
        secondLevel.style.height = "0";
      }

    }, false);
  }
})();