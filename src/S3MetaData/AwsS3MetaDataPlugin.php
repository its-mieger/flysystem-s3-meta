<?php
	namespace S3MetaData;

	use Aws\S3\S3Client;
	use League\Flysystem\AwsS3v3\AwsS3Adapter;
	use League\Flysystem\Filesystem;
	use League\Flysystem\FilesystemInterface;
	use League\Flysystem\PluginInterface;

	/**
	 * This plugin allows to query all S3 meta data using the getAwsMetaData function. The function will return an empty array for non-s3 file systems
	 * @package S3Metadata
	 */
	class AwsS3MetaDataPlugin implements PluginInterface
	{
		/** @var Filesystem */
		protected $filesystem;

		public function getMethod() {
			return 'getAwsMetaData';
		}

		public function setFilesystem(FilesystemInterface $filesystem) {
			$this->filesystem = $filesystem;
		}

		public function handle($path) {
			/** @var AwsS3Adapter $adapter */
			$adapter = $this->filesystem->getAdapter();

			if (is_callable([$adapter, 'getClient'])) {
				$client = $adapter->getClient();

				if ($client instanceof S3Client) {

					$options = [
						'Key'    => $adapter->applyPathPrefix($path),
						'Bucket' => $adapter->getBucket()
					];

					$response = $client->headObject($options);

					return $response['MetaData'];
				}
			}


			// return empty array for non s3
			return [];
		}
	}