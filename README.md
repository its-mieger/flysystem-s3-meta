# AWS S3 meta data plugin

A plugin to access all meta data for S3 objects via [Flysystem](https://flysystem.thephpleague.com/).

## Example usage

	use Aws\S3\S3Client;
	use League\Flysystem\AwsS3v3\AwsS3Adapter;
	use League\Flysystem\Filesystem;
	use S3Metadata\AwsS3MetadataPlugin;

	$s3Client = new S3Client(['version' => '2006-03-01', 'region' => 'eu-central-1']);
	$filesystem = new Filesystem(new AwsS3Adapter($s3Client));
	$filesystem->addPlugin(new AwsS3MetadataPlugin());
	
	$metaData = $filesystem->getAwsMetaData('key/to/object');
	 
	 
If the plugin is added to another filesystem than S3 the `getAwsMetaData` function will simply
return an empty array.