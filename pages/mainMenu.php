<div id="tabs">
	<ul>
		<li class="tab <?php if ($pageName == 'servers') echo 'tabselected'; ?>">
			<a href="<?php echo MONITIS_APP_URL ?>&monitis_page=servers">Servers</a>
		</li>
		<li class="tab  <?php if ($pageName == 'configure') echo 'tabselected'; ?>">
			<a href="<?php echo MONITIS_APP_URL ?>&monitis_page=configure">Settings</a>
		</li>
		<li class="tab  <?php if ($pageName == 'monitisAccount') echo 'tabselected'; ?>">
			<a href="<?php echo MONITIS_APP_URL ?>&monitis_page=monitisAccount">Monitis Account</a>
		</li>
	</ul>
</div>