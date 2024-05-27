$(function(){
  $("#email_address").selectize({
    delimiter: ',',
    persist: false,
    create: true
  });

  $("#category").selectize({
    create: true,
    valueField: 'id',
    labelField: 'title',
    searchField: 'title',
    options:[
      {id:1, title: 'GMMS'},
      {id:2, title: 'KMS'}
    ]
  });

  $("#contact_nos").selectize({
    delimiter: ',',
    persist: false,
    create: true
  });

  $("#rateYo").rateYo({
    fullStar: true,
     starWidth: "23px"
  });

});
