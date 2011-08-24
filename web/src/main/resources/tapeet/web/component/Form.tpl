<form
		action="/index.php"
		method="post"
		{if $c->id}
			id="{$c->id|escape}"
		{/if}
	>
	{$c->content}
	<div>
		<input type="hidden" name="_component" value="{$c->getName()}" />
		{foreach item=parameter from=$c->getPageContext()}
			<input type="hidden" name="{$parameter->name}" value="{$parameter->getValue()|escape}" />
		{/foreach}
	</div>
</form>
