@if(($badgeType ?? '') === 'organisasi' || $experience->type?->value === 'organization')
    @include('portfolio.partials.organization-card', ['experience' => $experience])
@else
    @include('portfolio.partials.internship-card', ['experience' => $experience])
@endif
