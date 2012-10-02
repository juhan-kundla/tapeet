<form
		action="/index.php"
		method="post"
		{if $this->id}
			id="{$this->id|escape}"
		{/if}
	>
	{$this->content}
	<div>
		<input type="hidden" name="_component" value="{$this->getName()}" />
		{foreach item=parameter from=$this->getPageContext()}
			<input type="hidden" name="{$parameter->name}" value="{$parameter->getValue()|escape}" />
		{/foreach}
	</div>
</form>
