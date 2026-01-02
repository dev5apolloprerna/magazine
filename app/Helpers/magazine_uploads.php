<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (! function_exists('magazine_target_root')) {
    /**
     * Pick the correct disk root for writes:
     * - If live dir exists (../public_html/anvixa), use that
     * - Else fallback to local public/anvixa
     */
    function magazine_target_root(): string
    {
        $live = anvixa_base_path(); // from your snippet
        if (File::isDirectory($live)) {
            return $live;
        }
        return public_path('anvixa');
    }
}

if (! function_exists('magazine_base_url')) {
    /**
     * Public base URL for the same content.
     * Uses your anvixa_base_url(), and falls back to app url + /anvixa
     */
    function magazine_base_url(string $append = ''): string
    {
        $base = anvixa_base_url(); // from your snippet
        // If you're running locally and the above already resolves correctly, remove the fallback:
        if (!$base) {
            $base = rtrim(config('app.url'), '/') . '/anvixa';
        }
        return $append ? rtrim($base, '/') . '/' . ltrim($append, '/') : rtrim($base, '/');
    }
}

if (! function_exists('magazine_upload')) {
    /**
     * Move an uploaded file to anvixa/uploads/{subdir}/{filename}
     * Returns array: [relative, url, filename, mime, size]
     *
     * @param UploadedFile $file
     * @param string $subdir (e.g. 'gallery', 'documents')
     * @param array|null $allowedExt override allowed extensions
     */
    function magazine_upload(UploadedFile $file, string $subdir, ?array $allowedExt = null): array
    {
        $allowed = $allowedExt ?: [
            // images
            'jpg','jpeg','png','webp','gif',
            // docs
            'pdf','doc','docx','xls','xlsx','ppt','pptx','txt'
        ];

        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowed, true)) {
            abort(422, "File type .$ext not allowed.");
        }

        $root   = magazine_target_root();                                 // filesystem root
        $dirRel = 'uploads/' . trim($subdir, '/');                   // relative dir under anvixa
        $dirAbs = rtrim($root, '/') . '/' . $dirRel;                 // absolute dir
        ensure_dir($dirAbs);                                         // from your snippet

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug     = Str::slug($baseName) ?: 'file';
        $filename = uniqid().'-'.$slug.'.'.$ext;

        $file->move($dirAbs, $filename);

        $relative = $dirRel . '/' . $filename;                       // store in DB (portable)
        $url      = magazine_base_url($relative);                          // public url

        return [
            'relative' => $relative,
            'url'      => $url,
            'filename' => $filename,
            'mime'     => File::mimeType($dirAbs . '/' . $filename) ?: $file->getMimeType(),
            'size'     => File::size($dirAbs . '/' . $filename),
        ];
    }
}

if (! function_exists('magazine_delete')) {
    /**
     * Delete a previously stored file using its DB 'relative' path (uploads/...).
     */
    function magazine_delete(?string $relative): void
    {
        if (!$relative) return;
        $abs = rtrim(magazine_target_root(), '/') . '/' . ltrim($relative, '/');
        if (File::exists($abs)) {
            File::delete($abs);
        }
    }
}

if (! function_exists('magazine_url')) {
    /**
     * Build public URL from stored relative path.
     */
    function magazine_url(?string $relative): ?string
    {
        return $relative ? magazine_base_url($relative) : null;
    }
}
