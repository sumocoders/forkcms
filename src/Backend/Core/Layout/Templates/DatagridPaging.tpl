{option:pagination}
{* there is more than 1 page *}
{option:pagination.multiple_pages}
<nav>
  <ul class="pagination justify-content-center">
    <li class="page-item{option:!pagination.show_previous} disabled{/option:!pagination.show_previous}">
    {option:!pagination.show_previous}
      <span class="page-link">
      {/option:!pagination.show_previous}
      {option:pagination.show_previous}
      <a class="page-link" href="{$pagination.previous_url}" aria-label="{$previousLabel|ucfirst}">
      {/option:pagination.show_previous}
        <span aria-hidden="true">&laquo;</span>
      {option:pagination.show_previous}
      </a>
      {/option:pagination.show_previous}
      {option:!pagination.show_previous}
      </span>
    {/option:!pagination.show_previous}
    </li>
    {option:pagination.first}
    {iteration:pagination.first}
    <li class="page-item">
      <a class="page-link" href="{$pagination.first.url}" rel="nofollow" title="{$goToLabel|ucfirst} {$pagination.first.label}">
        {$pagination.first.label}
      </a>
    </li>
    {/iteration:pagination.first}
    <li class="page-item disabled ellipsis">
      <span class="page-link">&hellip;</span>
    </li>
    {/option:pagination.first}
    {iteration:pagination.pages}
    {option:pagination.pages.current}
    <li class="page-item active">
      <span class="page-link">{$pagination.pages.label}<span class="visually-hidden">({$lblPaginationCurrent})</span></span>
    </li>
    {/option:pagination.pages.current}
    {option:!pagination.pages.current}
    <li class="page-item">
      <a class="page-link" href="{$pagination.pages.url}" rel="nofollow" title="{$goToLabel|ucfirst} {$pagination.pages.label}">
        {$pagination.pages.label}
      </a>
    </li>
    {/option:!pagination.pages.current}
    {/iteration:pagination.pages}
    {option:pagination.last}
    <li class="page-item disabled ellipsis"><span class="page-link">&hellip;</span></li>
    {iteration:pagination.last}
    <li class="page-item">
      <a class="page-link" href="{$pagination.last.url}" rel="nofollow" title="{$goToLabel|ucfirst} {$pagination.last.label}">
        {$pagination.last.label}
      </a>
    </li>
    {/iteration:pagination.last}
    {/option:pagination.last}
    <li class="page-item{option:!pagination.show_next} disabled{/option:!pagination.show_next}">
    {option:!pagination.show_next}
      <span class="page-link">
      {/option:!pagination.show_next}
      {option:pagination.show_next}
      <a class="page-link" href="{$pagination.next_url}" aria-label="{$nextLabel|ucfirst}">
      {/option:pagination.show_next}
        <span aria-hidden="true">&raquo;</span>
      {option:pagination.show_next}
      </a>
      {/option:pagination.show_next}
      {option:!pagination.show_next}
      </span>
    {/option:!pagination.show_next}
    </li>
  </ul>
</nav>
{/option:pagination.multiple_pages}
{/option:pagination}
