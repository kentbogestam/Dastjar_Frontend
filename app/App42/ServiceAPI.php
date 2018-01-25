<?php
namespace App\App42;

//use com\shephertz\app42\paas\sdk\php\appTab\Discount;
use App\App42\catalogue;

use App\App42\UserService;
use App\App42\UploadService;
use App\App42\Upload;
use App\App42\UploadFileType;
use App\App42\ReviewService;
use App\App42\Review;
use App\App42\SessionService;
use App\App42\Recommender;
use App\App42\RecommenderService;
use App\App42\Cart;
use App\App42\PaymentStatus;
use App\App42\CartService;
use App\App42\Cart;
use App\App42\Storage;
use App\App42\StorageService;
use App\App42\Geo;
use App\App42\GeoService;
use App\App42\GeoPoint;
use App\App42\QueueService;
use App\App42\Queue;
use App\App42\Album;
use App\App42\AlbumService;
use App\App42\PhotoService;
use App\App42\EmailService;
use App\App42\Email;
use App\App42\EmailMIME;
use App\App42\GameService;
use App\App42\RewardService;
use App\App42\ScoreService;
use App\App42\ScoreBoardService;
use App\App42\Logging;
use App\App42\LogService;
use App\App42\CatalogueService;
use App\App42\Image;
use App\App42\ImageProcessorService;
use App\App42\Social;
use App\App42\SocialService;
use App\App42\PushNotificationService;
use App\App42\AchievementService;
use App\App42\Achievement;
use App\App42\App42Config;
use App\App42\ABTestService;
use App\App42\ABTesting;
use App\App42\Buddy;
use App\App42\BuddyService;
use App\App42\AvatarService;
use App\App42\Avatar;
use App\App42\MetaResponse;
use App\App42\BravoBoardService;


/**
 * This class basically is a factory class which builds the service for use.
 * All services can be instantiated using this class
 * 
 */
class ServiceAPI {

    protected $apiKey;
    protected $secretKey;
    protected $url;
    protected $contentType;
    protected $accept;
    private $config;
    private $baseURL;

    /**
     * this is a constructor that takes
     * @param  apiKey
     * @param  secretKey
     *
     */
    public function __construct($apiKey, $secretKey) {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    /**
     * Retrieve the value of config object.
     *
     * @return Config object
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Sets the value of config object
     *
     * @param config
     *            Config object
     *
     * @see Config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    public function setBaseURL($protocol, $host, $port) {
        App42Config::getInstance()->setBaseURL($protocol, $host, $port);
    }

    // BUILDING FUNCTIONS FOR ALL THE API'S

    public function buildUserService() {
        $objUser = new UserService($this->apiKey, $this->secretKey);
        return $objUser;
    }

    public function buildUploadService() {
        $objUpload = new UploadService($this->apiKey, $this->secretKey);
        return $objUpload;
    }

    public function buildReviewService() {
        $objReview = new ReviewService($this->apiKey, $this->secretKey);
        return $objReview;
    }

    public function buildSessionService() {
        $objSession = new SessionService($this->apiKey, $this->secretKey);
        return $objSession;
    }

    public function buildRecommenderService() {
        $objRecommender = new RecommenderService($this->apiKey, $this->secretKey);
        return $objRecommender;
    }

    public function buildCartService() {
        $objCart = new CartService($this->apiKey, $this->secretKey);
        return $objCart;
    }

    public function buildCatalogueService() {
        $objCatalogue = new CatalogueService($this->apiKey, $this->secretKey);
        return $objCatalogue;
    }

    public function buildStorageService() {
        $objStorage = new StorageService($this->apiKey, $this->secretKey);
        return $objStorage;
    }

    public function buildGeoService() {
        $objGeo = new GeoService($this->apiKey, $this->secretKey);
        return $objGeo;
    }

    public function buildQueueService() {
        $objQueue = new QueueService($this->apiKey, $this->secretKey);
        return $objQueue;
    }

    public function buildAlbumService() {
        $objAlbum = new AlbumService($this->apiKey, $this->secretKey);
        return $objAlbum;
    }

    public function buildPhotoService() {
        $objPhoto = new PhotoService($this->apiKey, $this->secretKey);
        return $objPhoto;
    }

    public function buildEmailService() {
        $objEmail = new EmailService($this->apiKey, $this->secretKey);
        return $objEmail;
    }

    public function buildGameService() {
        $objGame = new GameService($this->apiKey, $this->secretKey);
        return $objGame;
    }

    public function buildRewardService() {
        $objReward = new RewardService($this->apiKey, $this->secretKey);
        return $objReward;
    }

    public function buildScoreService() {
        $buildScore = new ScoreService($this->apiKey, $this->secretKey);
        return $buildScore;
    }

    public function buildScoreBoardService() {
        $buildScoreBoard = new ScoreBoardService($this->apiKey, $this->secretKey);
        return $buildScoreBoard;
    }

    public function buildLogService() {
        $buildLog = new LogService($this->apiKey, $this->secretKey);
        return $buildLog;
    }

    public function buildImageProcessorService() {
        $buildImageProcessor = new ImageProcessorService($this->apiKey, $this->secretKey);
        return $buildImageProcessor;
    }

    public function buildSocialService() {
        $buildSocial = new SocialService($this->apiKey, $this->secretKey);
        return $buildSocial;
    }

    public function buildPushNotificationService() {
        $pushSocial = new PushNotificationService($this->apiKey, $this->secretKey);
        return $pushSocial;
    }

    public function buildAchievementService() {
        $achievementService = new AchievementService($this->apiKey, $this->secretKey);
        return $achievementService;
    }

    public function buildABTestService() {
        $abTestService = new ABTestService($this->apiKey, $this->secretKey);
        return $abTestService;
    }

    public function buildBuddyService() {
        $buddyService = new BuddyService($this->apiKey, $this->secretKey);
        return $buddyService;
    }

    public function buildAvatarService() {
        $avatarService = new AvatarService($this->apiKey, $this->secretKey);
        return $avatarService;
    }

    public function buildGiftService() {
        $giftService = new GiftService($this->apiKey, $this->secretKey);
        return $giftService;
    }

    public function buildTimerService() {
        $timerService = new TimerService($this->apiKey, $this->secretKey);
        return $timerService;
    }
       public  function buildBravoBoardService() {
		$bravoBoardService = new BravoBoardService($this->apiKey, $this->secretKey);
		return $bravoBoardService;
	}
}

?>