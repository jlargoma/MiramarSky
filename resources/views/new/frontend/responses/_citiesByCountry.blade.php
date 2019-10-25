<label for="city">CIUDAD</label>
<select class="form-control country minimal" name="city">
<?php foreach ($cities as $city): ?>
    <option value="<?php echo $city->id ?>"><?php echo $city->city ?></option>
<?php endforeach ?>
</select>