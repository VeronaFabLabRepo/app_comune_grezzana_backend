<div class="content clearfix">

	<div class="page_title clearfix">

		<h1>Factotum (alpha) - Version Log - <?php echo $factotumVersion; ?></h1>

	</div>

	<div class="factotum_version">

		<h3>ChangeLog</h3>

		<h4 class="blue">1.3.0</h4>
		<p>
			Important fix on the getContentList function
		</p>

		<h4 class="blue">1.2.5</h4>
		<p>
			(Minor Change) Fix the users update (now also the role is updated)
		</p>

		<h4 class="blue">1.2.4</h4>
		<p>
			(Minor Change) Added autocomplete param to print_form_helper
		</p>

		<h4 class="blue">1.2.3</h4>
		<p>
			Add only logged user control on the pages (useful if we want that a page is visible only from logged user).
		</p>

		<h4 class="blue">1.2.2</h4>
		<p>
			Add form input helper to print input hidden (overwritten the codeigniter native one's, doesnt give the chance of adding an id attribute).
		</p>

		<h4 class="blue">1.2.1</h4>
		<p>
			Like suggested from <a href="mailto:ben@chromaagency.com">Ben</a>, I've added a link to edit function for each list on the backend part.
		</p>

		<h4 class="blue">1.2.0</h4>
		<p>
			Moved the saveContent function (and all the other function for saving attachs, processing images, etc) into the FM_Cms library. 
			This give me an easy way to put on the frontend the chance that user can add content.
		</p>


		<br><br><br>


		<h3>Features</h3>

		<h4 class="blue">Users Management</h4>
		<p>Only the users that have the "user management" permission can insert, edit or delete a user.</p>

		<h4 class="blue">Users Roles &amp; Capabilities</h4>
		<p>
			Management of the user roles and the user capabilities for each content.<br>
			Only the users that have the "user management" permission can insert, edit or delete a user role.<br><br> 
			An user can :<br>
			<strong>Access to the backend</strong><br>
			<strong>Manage the content types</strong><br>
			<strong>Manage the users (the above feature and the user roles too)</strong><br><br>
			The capabilities for each content type are:<br>
			<strong><em>Configure</em></strong> : the user can choose the content fields for the content.<br>
			<strong><em>Edit</em></strong> : the user can insert, edit or delete a content.<br>
			<strong><em>Publish</em></strong> : the user can publish the content.
		</p>

		<h4 class="blue">Content Types</h4>
		<p>
			A content type is the representation of an entity.<br>
			Only the users that have the "Manage Content Types" permission can insert, edit or delete a content type.
		</p>

		<h4 class="blue">Content Fields</h4>
		<p>
			The content fields are the fields that belong to an entity.<br>
			Only the users that have the "Configure" capability for the content type can insert, edit or delete a content field.<br>
			The content fields available are:<br>
			<ul>
				<li>text</li>
				<li>textarea</li>
				<li>XHTML textarea</li>
				<li>select</li>
				<li>multi select</li>
				<li>radio</li>
				<li>checkbox</li>
				<li>multi checkbox</li>
				<li>date</li>
				<li>datetime</li>
				<li>image upload</li>
				<li>file upload</li>
				<li>gallery</li>
				<li>linked content</li>
				<li>multiple linked content</li>
			</ul>
		</p>

		<h4 class="blue">Content</h4>
		<p>
			The content are represented like an entity.<br>
			Each content must have a unique permalink, that help the system to recognize the content.<br>
			Only the users that have the "Edit" capability for the content type can insert, edit or delete a content.<br>
			Only the users that have the "Publish" capability for the content type can publish a content.
		</p>

		<h4 class="blue">Pages</h4>
		<p>
			The pages are a particular type of content.<br>
			Only the users that have the "Edit" capability for the content type "pages" can insert, edit or delete a page.<br>
			Only the users that have the "Publish" capability for the content type "pages" can publish a page.<br>
			There are particular fields for this content type
		</p>

	</div>
</div>
