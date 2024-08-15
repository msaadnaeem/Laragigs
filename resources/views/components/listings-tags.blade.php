@props(['tags'])

<ul class="flex">
    @foreach (explode(',', $tags) as $tag)
        <li class="bg-black text-white rounded-xl px-3 py-1 mr-2">
            <a href="/?tag={{ trim($tag) }}">{{ Str::title(trim($tag)) }}</a>
        </li>
    @endforeach
</ul>
