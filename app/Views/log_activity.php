<section class="section">
<div class="col-lg-6 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Log Activity</h4>
     
      <!-- Filter Form -->
      <form action="<?= base_url('home/log_activity') ?>" method="get" class="form-inline mb-3">
        <div class="form-group">
          <label for="userFilter" class="mr-2">Filter by User: </label>
          <select name="user_id" id="userFilter" class="form-control mr-2">
            <option value="">All Users</option>
            <?php foreach ($users as $user): ?>
              <option value="<?= $user['id_user'] ?>" <?= isset($_GET['user_id']) && $_GET['user_id'] == $user['id_user'] ? 'selected' : '' ?>>
                <?= $user['username'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-warning">Filter</button>
      </form>
      
      <!-- Delete All Logs by Filtered User Form -->
      

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID User</th>
              <th>Username</th>
              <th>Menu</th>
              <th>Waktu</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as $activity): ?>
              <tr>
                <td><?= $activity['id_user'] ?></td>
                <td><?= $activity['username'] ?></td>
                <td><?= $activity['menu'] ?></td>
                <td><?= $activity['time'] ?></td>
                <td>
                  <!-- Delete button for each log entry -->
                  <form action="<?= base_url('home/hapus_log') ?>" method="post" style="display:inline;">
                    <input type="hidden" name="id_log" value="<?= $activity['id_log'] ?>"> <!-- Correct field name -->
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity log?')">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</section>