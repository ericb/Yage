<p>
	This is the default controller view for the HomeController
</p>

<p>
    Here is a list of names from the database:
</p>

<ul>
    {foreach $people as $person}
            <li>{$person.name}</li>
    {/foreach}
</ul>