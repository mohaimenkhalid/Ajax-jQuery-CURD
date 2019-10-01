<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AJAX CURD</title>
        <!-- Fonts -->
                 <script
                      src="https://code.jquery.com/jquery-3.4.1.js"
                      integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
                      crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        
    </head>
    <body>
        <div class="container">
            
           <div class="col-md-12 mt-4">
               
                <div class="clearfix">
                    <span>AJAX-JQUERY CURD</span>
                    
                    <button type="button" class="btn btn-success btn-sm float-right text-white" data-toggle="modal" onClick="create()" data-whatever="@mdo">Add New</button>
                    <hr>
                </div>



                 <!--Add Modal -->
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form id="myform">

                                        <input type="hidden" name="id">
                                      <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Name:</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                      </div>
                                      <div class="form-group">
                                        <label for="message-text" class="col-form-label">Email:</label>
                                         <input type="email" class="form-control" id="email" name="email">
                                      </div>

                                      <div class="form-group">
                                        <label for="message-text" class="col-form-label">Phone:</label>
                                         <input type="text" class="form-control" id="phone" name="phone">
                                      </div>

                                      <div class="form-group">
                                        <label for="message-text" class="col-form-label">Religion:</label>
                                         <input type="text" class="form-control" id="religion" name="religion">
                                      </div>
                                    </form>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary btn-sm" onClick="store()" id="btnSave">Create</button>
                                    <button type="button" class="btn btn-primary btn-sm" onClick="update()" id="btnUpdate">Update</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                    {{-- Table --}}

                    <table class="table">
                              <thead class="thead-light">
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Name</th>
                                  <th scope="col">Email</th>
                                  <th scope="col">Phone No</th>
                                  <th scope="col">Religion</th>
                                  <th scope="col">Acton</th>
                                </tr>
                              </thead>
                              <tbody>
                               
                              </tbody>
                    </table>

           </div>
        </div>


<script>
        var modal = $('#modal');
        var btnSave = $('#btnSave');
        var btnUpdate = $('#btnUpdate');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        function getRecords(){
            $.get('/contacts/data')
                .done(function(data){
                    var html='';
                    data.forEach(function(row){
                        html += '<tr>'
                        html += '<td>' + row.id + '</td>'
                        html += '<td>' + row.name + '</td>'
                        html += '<td>' + row.email + '</td>'
                        html += '<td>' + row.phone + '</td>'
                        html += '<td>' + row.religion + '</td>'
                        html += '<td>'
                        html += '<button class="btn btn-warnning btn-sm" id="btnEdit" title="Edit Record">Edit</button> '
                        html += '<button class="btn btn-danger btn-sm" id="btnDelete" data-id="'+ row.id +'" title="delete record">Delete</button>'
                        html += '<td> </tr>' ;
                    })

                    $('table tbody').html(html);
                })
        }

        //open modal for create
        function create(){
            $('#modal').find('.modal-title').text('Add New Contact');
            document.getElementById("myform").reset();
            $('#modal').modal('show')
            $('#btnSave').show()
            $('#btnUpdate').hide()
        }

        //get input

        function getInputs(){
            var id = $('input[name="id"]').val();
            var name = $('input[name="name"]').val();
            var email = $('input[name="email"]').val();
            var phone = $('input[name="phone"]').val();
            var religion = $('input[name="religion"]').val();

            return { id: id, name: name, email: email, phone: phone, religion: religion }
        }

        function store(){

           /*$.post( "/contacts/store", getInputs(), function( data ) {
              console.log('Done');
                $('#modal').modal('hide');
                getRecords();
            });*/
            $.ajax({
              method: "POST",
              url: '/contacts/store',
              data: getInputs(),
              dataType: 'JSON',
              success: function(data){
                //data return data for notification
                console.log('Inserted');
                $('#modal').modal('hide');
                getRecords();
              }
            });
        }

        $('table').on('click', '#btnEdit', function(){

            modal.find('.modal-title').text('Update Contact Record');
            document.getElementById("myform").reset();
            $('#modal').modal('show')
            $('#btnSave').hide()
            $('#btnUpdate').show()

            //pick data from table
            var id = $(this).parent().parent().find('td').eq(0).text();
            var name = $(this).parent().parent().find('td').eq(1).text();
            var email = $(this).parent().parent().find('td').eq(2).text();
            var phone = $(this).parent().parent().find('td').eq(3).text();
            var religion = $(this).parent().parent().find('td').eq(4).text();

            //set data from selected id

            var id = $('input[name="id"]').val(id);
            var name = $('input[name="name"]').val(name);
            var email = $('input[name="email"]').val(email);
            var phone = $('input[name="phone"]').val(phone);
            var religion = $('input[name="religion"]').val(religion);
        })

        //update data
        function update(){
            $.ajax({
              method: "POST",
              url: '/contacts/update',
              data: getInputs(),
              dataType: 'JSON',
              success: function(){
                console.log('updated');
                document.getElementById("myform").reset();
                $('#modal').modal('hide');
                getRecords();
              }
            });
        }

        //delete data
         $('table').on('click', '#btnDelete', function(){
            if (!confirm('Are you sure to delete this record?')) return;

            var id = $(this).data('id'); //get data (data-id)
            var data = {id: id}

            $.ajax({
              method: "POST",
              url: '/contacts/delete',
              data: data,
              dataType: 'JSON',
              success: function(){
                console.log('deleted');
                getRecords();
              }
            });

         })



        getRecords();

</script>


    </body>
</html>
