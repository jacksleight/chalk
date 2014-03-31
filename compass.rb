css_dir				= "public/build"
sass_dir			= "public/styles"
images_dir			= "public/images"
javascripts_dir		= "public/scripts"
fonts_dir			= "public/fonts"

output_style		= :expanded
relative_assets		= true
line_comments		= false

asset_cache_buster do |path, real_path|
	if File.exists?(real_path)
		pathname = Pathname.new(path)
		modified_time = File.mtime(real_path).strftime("%s")
		new_path = "%s/%s.%s%s" % [pathname.dirname, pathname.basename(pathname.extname), modified_time, pathname.extname]
		{:path => new_path, :query => nil}
	end
end