<?php 
	
	echo'<form id="horizontalForm" action="">
			<fieldset>
				<legend>
					Name
				</legend>
				<label class="first" for="title1">
					Title
					<select id="title1" name="title1">
						<option selected="selected">Mr.</option>
						<option>Mrs.</option>
						<option>Ms.</option>
						<option>Dr.</option>
						<option>Viscount</option>
					</select>
				</label>
				<label for="firstName1">
					First name
					<input id="firstName1" name="firstName1" type="text" value="First name" />
				</label>
				<label for="lastName1">
					Last name
					<input id="lastName1" name="lastName1" type="text" value="Last name" />
				</label>
			</fieldset>
			<fieldset>
				<legend>
					Address
				</legend>
				<label class="first" for="street1">
					Street
					<input id="street1" name="street1" type="text" value="Street" />
				</label>
				<label for="city1">
					City
					<input id="city1" name="city1" type="text" value="City" />
				</label>
				<label for="state1">
					State
					<input id="state1" name="state1" type="text" value="State" />
				</label>
				<label for="postcode1">
					Postcode
					<input id="postcode1" name="postcode1" type="text" value="Postcode" />
				</label>
				<label for="country1">
					Country
					<input id="country1" name="country1" type="text" value="Country" />
				</label>
			</fieldset>
			<fieldset>
				<legend>
					Payment details
				</legend>
				<fieldset class="radio">
					<legend>
						Credit card
					</legend>
					<label for="cardType1A">
						<input id="cardType1A" name="card1" type="radio" />
						American Express
					</label>
					<label for="cardType1B">
						<input id="cardType1B" name="card1" type="radio" />
						Mastercard
					</label>
					<label for="cardType1C">
						<input id="cardType1C" name="card1" type="radio" />
						Visa
					</label>
					<label for="cardType1D">
						<input id="cardType1D" name="card1" type="radio" />
						Blockbuster Card
					</label>
				</fieldset>
				<label for="cardNum1">
					Card number
					<input id="cardNum1" name="cardNum1" type="text" value="Card number" />
				</label>
				<label for="expiry1">
					Expiry date
					<input id="expiry1" name="city1" type="text" value="City" />
				</label>
				<input class="submit" type="submit" value="Submit my details" />
			</fieldset>
		</form>';
?>