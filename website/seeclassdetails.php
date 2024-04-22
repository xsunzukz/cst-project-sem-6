<div class="edit-form">
    <h2>Edit Class</h2>
    <form action="./events/edit_class.php" method="post">
        <label for="edit_id">Class ID:</label>
        <input type="number" id="edit_id" name="edit_id" required>
        <select id="edit_dept" name="edit_dept" required>
            <option value="">Select Department</option>
            <option value="CST">CST</option>
            <option value="CFS">CFS</option>
            <option value="ID">ID</option>
            <option value="ELECTRICAL">ELECTRICAL</option>
            <option value="MECHATRONICS">MECHATRONICS</option>
        </select>
        
        <input type="submit" value="Get Info">
    </form>