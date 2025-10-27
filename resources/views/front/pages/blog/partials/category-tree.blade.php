@php
    $hasChildren = $category->children && $category->children->count() > 0;
    $indentClass = 'level-' . $level;

    // Use pre-calculated total post count or calculate it
    $totalPostCount = isset($category->total_post_count) ? $category->total_post_count : $category->posts()->count();
@endphp

<div class="category-item {{ $indentClass }}">
    <a href="{{ request()->routeIs('blog.category') ? route('blog.category', $category->slug) : route('blog.index', ['category' => $category->slug]) }}"
        class="list-group-item d-flex align-items-center justify-content-between {{ $selectedCategory == $category->slug ? 'active' : '' }}">
        <span class="category-name">
            {{ $category->name }}
        </span>
        <div class="category-controls">
            <span class="badge badge-primary badge-pill">{{ $totalPostCount }}</span>
            @if ($hasChildren)
                <button class="dropdown-toggle" type="button" data-toggle="subcategories-{{ $category->id }}">
                </button>
            @endif
        </div>
    </a>

    @if ($hasChildren)
        <div class="subcategories-dropdown" id="subcategories-{{ $category->id }}" style="display: none;">
            @foreach ($category->children as $child)
                @php
                    $childHasChildren = $child->children && $child->children->count() > 0;

                    // Calculate total post count for child including all its descendants
                    $childTotalPostCount = $child->posts()->count();
                    if ($childHasChildren) {
                        foreach ($child->children as $subChild) {
                            $childTotalPostCount += $subChild->posts()->count();
                            if ($subChild->children && $subChild->children->count() > 0) {
                                foreach ($subChild->children as $subSubChild) {
                                    $childTotalPostCount += $subSubChild->posts()->count();
                                }
                            }
                        }
                    }
                @endphp
                <div class="category-item level-{{ $level + 1 }}">
                    <a href="{{ request()->routeIs('blog.category') ? route('blog.category', $child->slug) : route('blog.index', ['category' => $child->slug]) }}"
                        class="list-group-item d-flex align-items-center justify-content-between {{ $selectedCategory == $child->slug ? 'active' : '' }}">
                        <span class="category-name">
                            {{ $child->name }}
                        </span>
                        <div class="category-controls">
                            <span class="badge badge-primary badge-pill">{{ $childTotalPostCount }}</span>
                            @if ($childHasChildren)
                                <button class="dropdown-toggle" type="button"
                                    data-toggle="subcategories-{{ $child->id }}">
                                </button>
                            @endif
                        </div>
                    </a>

                    @if ($childHasChildren)
                        <div class="subcategories-dropdown" id="subcategories-{{ $child->id }}"
                            style="display: none;">
                            @foreach ($child->children as $subChild)
                                @php
                                    $subChildHasChildren = $subChild->children && $subChild->children->count() > 0;

                                    // Calculate total post count for sub-child including all its descendants
                                    $subChildTotalPostCount = $subChild->posts()->count();
                                    if ($subChildHasChildren) {
                                        foreach ($subChild->children as $subSubChild) {
                                            $subChildTotalPostCount += $subSubChild->posts()->count();
                                        }
                                    }
                                @endphp
                                <div class="category-item level-{{ $level + 2 }}">
                                    <a href="{{ request()->routeIs('blog.category') ? route('blog.category', $subChild->slug) : route('blog.index', ['category' => $subChild->slug]) }}"
                                        class="list-group-item d-flex align-items-center justify-content-between {{ $selectedCategory == $subChild->slug ? 'active' : '' }}">
                                        <span class="category-name">
                                            {{ $subChild->name }}
                                        </span>
                                        <div class="category-controls">
                                            <span
                                                class="badge badge-primary badge-pill">{{ $subChildTotalPostCount }}</span>
                                            @if ($subChildHasChildren)
                                                <button class="dropdown-toggle" type="button"
                                                    data-toggle="subcategories-{{ $subChild->id }}">
                                                </button>
                                            @endif
                                        </div>
                                    </a>

                                    @if ($subChildHasChildren)
                                        <div class="subcategories-dropdown" id="subcategories-{{ $subChild->id }}"
                                            style="display: none;">
                                            @foreach ($subChild->children as $subSubChild)
                                                <div class="category-item level-{{ $level + 3 }}">
                                                    <a href="{{ request()->routeIs('blog.category') ? route('blog.category', $subSubChild->slug) : route('blog.index', ['category' => $subSubChild->slug]) }}"
                                                        class="list-group-item d-flex align-items-center justify-content-between {{ $selectedCategory == $subSubChild->slug ? 'active' : '' }}">
                                                        <span class="category-name">
                                                            {{ $subSubChild->name }}
                                                        </span>
                                                        <div class="category-controls">
                                                            <span
                                                                class="badge badge-primary badge-pill">{{ $subSubChild->posts()->count() }}</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
