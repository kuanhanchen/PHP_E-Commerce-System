

<h1 class="page-header">
   All Reports

</h1>

<!-- display delete msg -->
<h4 class="bg-success"><?php display_message(); ?></h4>

<table class="table table-hover">


    <thead>

      <tr>
           <th>Id</th>
           <th>Product Id</th>
           <th>Order Id</th>
           <th>Price</th>
           <th>Product Title</th>
           <th>Product quantity</th>
      </tr>
    </thead>
    <tbody>
      
        <?php get_reports(); ?>

  </tbody>
</table>

