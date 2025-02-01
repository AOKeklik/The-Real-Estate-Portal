-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 01, 2025 at 09:30 AM
-- Server version: 11.3.2-MariaDB
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `the_real_estate_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `full_name`, `photo`, `password`, `token`, `status`) VALUES
(1, 'admin@mail.com', 'admin', '6767d58477a32.jpg', '$2y$10$6i2yOywDMcvrCdGEx8eNn.Kr5GzXxBWO/T3jDNkAPUnstHzorvTy2', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `photo` text DEFAULT NULL,
  `password` text NOT NULL,
  `company` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `biography` text DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `website` text DEFAULT NULL,
  `facebook` text DEFAULT NULL,
  `twitter` text DEFAULT NULL,
  `linkedin` text DEFAULT NULL,
  `pinterest` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `youtube` text DEFAULT NULL,
  `token` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `slug_2` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `slug`, `full_name`, `email`, `photo`, `password`, `company`, `designation`, `biography`, `phone`, `country`, `address`, `state`, `city`, `zip_code`, `website`, `facebook`, `twitter`, `linkedin`, `pinterest`, `instagram`, `youtube`, `token`, `status`) VALUES
(3, 'renard-nowak', 'Renard Nowak', 'agent@mail.pl', '67815230d7fa8.jpg', '$2y$10$ixARWlomZov5DJVg71MW.eA522msBULR8nskueFko8kCwALtMjr/S', 'Real Estate Agent', 'Real Estate Agent', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff; text-align: left;&quot;&gt;Hi, my name is Renard Nowak, and I am a licensed real estate agent with 8 years of experience in the industry. I have a passion for helping people find their dream home and making the buying and selling process as stress-free as possible.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff; text-align: left;&quot;&gt;I have a comprehensive understanding of the local real estate market and am knowledgeable in the latest market trends and developments. I take the time to listen to my clients&#039; needs and work tirelessly to find the perfect property that meets their requirements.&lt;/p&gt;', '333444555', 'Polska', 'Jan Kowalski ul. Nowy Świat 12/34', 'Mazowieckie', 'Warszawa', '00-001', 'https://www.InvitationTracker.pl', 'https://www.facebook.com', 'https://www.twitter.com', 'https://www.linkedin.com', 'https://www.pinterest.com', 'https://www.instagram.com', 'https://www.youtube.com', NULL, 1),
(5, 'jan-kowalski', 'Jan Kowalski', 'agent@jan.pl', '676fbd3d7ccf2.jpg', '$2y$10$ZGKSwibzEcIhkl2zCYo3i.J/1fevQe2xyHWkGlRAEcymqxwP3hP/i', 'Kowalski Nieruchomości', 'Doradca nieruchomości', '&lt;p&gt;Jan Kowalski jest doświadczonym doradcą nieruchomości z ponad 10-letnim stażem na rynku. Jego pasja do pracy w nieruchomościach zrodziła się w młodym wieku, kiedy to pom&amp;oacute;gł rodzinie znaleźć idealny dom. W trakcie swojej kariery specjalizował się w sprzedaży nieruchomości mieszkalnych oraz komercyjnych. Jan posiada bogate doświadczenie w negocjacjach i zarządzaniu projektami, co czyni go jednym z najlepszych w swojej branży.&lt;/p&gt;\r\n&lt;p&gt;W swojej pracy stawia na indywidualne podejście do klienta i pełne zrozumienie ich potrzeb. Dzięki swojej profesjonalnej obsłudze zdobył zaufanie wielu os&amp;oacute;b, kt&amp;oacute;re polecają go innym. Jan nieustannie poszerza swoją wiedzę, uczestnicząc w kursach i szkoleniach. Dzięki temu jest w stanie dostarczyć klientom najlepsze rozwiązania i dostosować ofertę do ich wymagań.&lt;/p&gt;', '+48 123 456 789', 'Polska', 'ul. Nowy Świat 12/34', 'Mazowieckie', 'Płock', '09-400', 'https://www.kowalski.com', 'https://www.facebook.com/jan.kowalski', 'https://www.twitter.com/jan_kowalski', 'https://www.linkedin.com/in/jan-kowalski', 'https://www.pinterest.com/jan.kowalski', 'https://www.instagram.com/jan.kowalski', 'https://www.youtube.com/jan.kowalski', NULL, 1),
(6, 'piotr-wisniewski', 'Piotr Wiśniewski', 'agent@piotr.pl', '676fbef22a466.jpg', '$2y$10$lMRvi2sWGlohfklIR5cSuOOkhoCl/LhAYmJtieKFnlZN.uOOoktJ6', 'Wiśniewski Nieruchomości', 'Doradca nieruchomości', '&lt;p&gt;Piotr Wiśniewski to dynamiczny doradca nieruchomości, kt&amp;oacute;ry swoją karierę zaczynał od pracy w małych biurach nieruchomości, a z biegiem lat awansował na stanowisko kierownicze. Specjalizuje się w sprzedaży luksusowych nieruchomości, gdzie jego profesjonalizm oraz umiejętności negocjacyjne są niezwykle cenione przez klient&amp;oacute;w. Piotr jest osobą, kt&amp;oacute;ra zawsze dąży do perfekcji i nie boi się wyzwań.&lt;/p&gt;\r\n&lt;p&gt;Piotr łączy swoją pasję do nieruchomości z chęcią pomocy innym. Jego podejście opiera się na zrozumieniu potrzeb klienta oraz dopasowaniu oferty do ich wymagań. Dzięki temu udało mu się nawiązać długotrwałe relacje biznesowe z wieloma klientami, kt&amp;oacute;rzy chętnie korzystają z jego usług przy kolejnych transakcjach.&lt;/p&gt;', '+48 345 678 901', 'Polska', 'ul. Mieszka 7/3', 'Mazowieckie', 'Płock', '09-400', 'https://www.wisniewski.com', 'https://www.facebook.com/piotr.wisniewski', NULL, NULL, NULL, NULL, NULL, NULL, 1),
(7, 'tomasz-kaczmarek', 'Tomasz Kaczmarek', 'agent@tomasz.pl', '676fbfebc8118.jpg', '$2y$10$uL/rgZImXs7YrYnx2ijqbulbYnJEGgbB.U7kXYDnxfLm0z3aNSgpK', 'Kaczmarek Nieruchomości', 'Doradca nieruchomości', '&lt;p&gt;Tomasz Kaczmarek to doradca nieruchomości z wieloletnim doświadczeniem w branży, specjalizujący się w sprzedaży nieruchomości komercyjnych. Jego praca z klientami biznesowymi oraz inwestorami daje mu nieocenioną przewagę na rynku nieruchomości. Tomasz jest doskonałym negocjatorem, kt&amp;oacute;ry zna potrzeby swoich klient&amp;oacute;w i skutecznie realizuje ich cele.&lt;/p&gt;\r\n&lt;p&gt;Jego pełne zaangażowanie w pracę oraz doskonała znajomość rynku sprawiają, że jest jednym z najbardziej cenionych doradc&amp;oacute;w w Płocku. Tomasz stawia na kompleksową obsługę klienta, oferując pełne wsparcie na każdym etapie transakcji. Jego celem jest zapewnienie klientom najwyższej jakości usług, kt&amp;oacute;re przynoszą długotrwałe korzyści.&lt;/p&gt;', '+48 567 890 123', 'Polska', 'ul. Wysoka 22/15', 'Mazowieckie', 'Płock', '09-400', 'https://www.kaczmarek.com', 'https://www.facebook.com/tomasz.kaczmarek', 'https://www.twitter.com/tomasz_kaczmarek', 'https://www.linkedin.com/in/tomasz-kaczmarek', 'https://www.pinterest.com/tomasz.kaczmarek', 'https://www.instagram.com/tomasz.kaczmarek', 'https://www.youtube.com/tomasz.kaczmarek', NULL, 1),
(8, 'grzegorz-lewandowski', 'Grzegorz Lewandowski', 'agent@grzegorz.pl', '676fc10fb3b51.jpg', '$2y$10$kQbzP8ceBfFnv3B6NP4Touolwo03B95rOnRnGMOdrJi3x5O20LRG2', 'Lewandowski Nieruchomości', 'Doradca nieruchomości', '&lt;p&gt;Grzegorz Lewandowski jest jednym z najbardziej doświadczonych doradc&amp;oacute;w nieruchomości w Płocku, specjalizującym się w sprzedaży nieruchomości komercyjnych. Pracując w tej branży od ponad 8 lat, zdobył niezbędną wiedzę i doświadczenie, kt&amp;oacute;re pozwala mu skutecznie doradzać klientom w kwestiach inwestycji i sprzedaży nieruchomości. Grzegorz jest także bardzo aktywny na rynku lokalnym, znany z wysokiej jakości usług i indywidualnego podejścia do każdego klienta.&lt;/p&gt;\r\n&lt;p&gt;Jego umiejętności negocjacyjne i analityczne pozwalają mu na realizację najbardziej skomplikowanych transakcji. Grzegorz zawsze stara się dostarczyć rozwiązania, kt&amp;oacute;re są korzystne dla wszystkich stron, zapewniając satysfakcję zar&amp;oacute;wno dla sprzedających, jak i kupujących.&lt;/p&gt;', '+48 789 012 345', 'Polska', 'ul. Górna 15/8', 'Mazowieckie', 'Płock', '09-400', 'https://www.lewandowski.com', 'https://www.facebook.com/grzegorz.lewandowski', 'https://www.twitter.com/grzegorz_lewandowski', 'https://www.linkedin.com/in/grzegorz-lewandowski', 'https://www.pinterest.com/grzegorz.lewandowski', 'https://www.instagram.com/grzegorz.lewandowski', 'https://www.youtube.com/grzegorz.lewandowski', NULL, 1),
(9, 'tomasz-dabrowski', 'Tomasz Dąbrowski', 'agent@dabrowski.pl', '67804c8473835.jpg', '$2y$10$DEM1bRnjT2YXaRp/.rX9bOji/cCNsHOL1Lniz.Q9xB9Pvi4Uu57dO', 'MarketingMax Nieruchomości', 'Marketing Head', NULL, '+48 505 321 987', 'Poland', 'ul. Królewska A 8/55', 'Pomorskie', 'Gdańsk', '80-005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(10, 'katarzyna-zielinska', 'Katarzyna Zielinska', 'agent@katarzyna.pl', NULL, '$2y$10$vBv1lYk7Try4xufkLu.rFuIKevL0PCrqGhj/Qu6IJi9quCIcvsa3K', 'HRPro Nieruchomości', 'HR Specialist', NULL, '+48 504 789 123', 'Poland', 'ul. Warszawska 20', 'Slaskie', 'Katowice', '40-004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(11, 'marek-zielinski', 'Marek Zieliński', 'agent@marek.pl', '678151d5d2328.jpg', '$2y$10$.ErodFuPC1.2IDbUYPqQ4.r9Yu7ImhZ7BsHWsXzhhponLrAuATi92', 'CodeCraft Solutions', 'Zieliński Nieruchomości', NULL, '+48 604 567 890', 'Poland', 'ul. Królewska 23/2', 'Wielkopolskie', 'Poznań', '61-758', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

DROP TABLE IF EXISTS `amenities`;
CREATE TABLE IF NOT EXISTS `amenities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `icon` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES
(4, 'Swimming Pool', 'fa fa-tint'),
(5, 'Parking Lot', 'fa fa-car'),
(6, 'Gym &amp; Fitness Center', 'fa fa-heart'),
(7, 'Internet Connection', 'fa fa-wifi'),
(8, 'Room Service', 'fa fa-bell'),
(9, 'Private Locker', 'fa fa-lock'),
(12, 'Elevators', 'fa fa-arrow-up'),
(13, 'Security', 'fa fa-user-secret'),
(14, 'Playground', 'fa fa-child'),
(18, 'Washing Machine', 'fa fa-cog');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `photo` text DEFAULT NULL,
  `token` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `email`, `password`, `photo`, `token`, `status`) VALUES
(9, 'Klimek Borkowski', 'customer@klimek.pl', '$2y$10$HV/BcOuNXv2b.cEk9GSkA.G1c9BPSeIpZmNaVAAu9gHjEjvcXmjhu', '67706ace612c3.jpg', NULL, 1),
(10, 'Zbigniew Kowalczyk', 'customer@zbigniew.pl', '$2y$10$JtaTQAkDmYV0ptTFxSjJweyUQpQOXVhtUAd0v0UevNYL0Afr7/IBa', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `status`) VALUES
(1, 'How do I buy a property?', 'The process typically involves finding a property, making an offer, conducting inspections, obtaining financing, and closing the deal. no.', 1),
(2, 'What is a real estate agent and what do they do?', 'A real estate agent is a licensed professional who helps buyers and sellers navigate the real estate process. They can assist with finding properties, negotiating deals, and handling paperwork.', 1),
(3, 'What is a mortgage?', 'A mortgage is a loan used to purchase a property. The property acts as collateral for the loan, and the buyer makes monthly payments to repay the loan over time.', 1),
(4, 'What is a home inspection?', 'A home inspection is a thorough examination of a property, performed by a licensed inspector, to assess its condition and identify any potential problems.', 1),
(5, 'What is property tax?', 'Property tax is a tax imposed by local government on real estate property, based on the value of the property. The tax is typically used to fund local services and infrastructure.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `slug` text NOT NULL,
  `photo` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `slug`, `photo`) VALUES
(1, 'Boston', 'boston', '6769c3c7ee293.jpg'),
(16, 'California', 'california', '6769c3da14d0f.jpg'),
(17, 'Chicago', 'chicago', '6769c3ee74bad.jpg'),
(18, 'Dallas', 'dallas', '6769c40b0ac2e.jpg'),
(19, 'Denver', 'denver', '6769c420af7dd.jpg'),
(20, 'New York', 'new-york', '6769c44e1e596.jpg'),
(21, 'San Diago', 'san-diago', '6769c474cf5e2.jpg'),
(22, 'Washington', 'washington', '6769c4892c23f.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_customer_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_agent_read` tinyint(1) NOT NULL DEFAULT 0,
  `posted_on` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `customer_id`, `agent_id`, `subject`, `message`, `is_customer_read`, `is_agent_read`, `posted_on`) VALUES
(23, 10, 3, 'What is your budget for purchasing a home?', '&lt;p&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi ab voluptas minima vel, accusamus perferendis?&lt;/p&gt;', 1, 1, '2025-01-13 18:36:48'),
(24, 9, 9, 'Which location or neighborhood are you interested in?', '&lt;p&gt;Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis, quis.&lt;/p&gt;', 1, 1, '2025-01-15 22:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `message_replies`
--

DROP TABLE IF EXISTS `message_replies`;
CREATE TABLE IF NOT EXISTS `message_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `reply` text NOT NULL,
  `reply_on` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `customer_id` (`customer_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_replies`
--

INSERT INTO `message_replies` (`id`, `message_id`, `customer_id`, `agent_id`, `sender`, `reply`, `reply_on`) VALUES
(8, 23, 10, 3, 'Agent', 'Lorem ipsum dolor, sit amet consectetur adipisicing.', '2025-01-13 18:38:17'),
(9, 23, 10, 3, 'Customer', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga ullam laudantium, corporis sequi omnis rem perferendis repellat. Quasi, dicta quisquam?', '2025-01-13 18:38:38'),
(10, 23, 10, 3, 'Agent', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis enim nisi itaque eius.', '2025-01-13 18:48:52'),
(11, 23, 10, 3, 'Customer', 'Lorem ipsum dolor sit amet consectetur adipisicing elit.', '2025-01-13 19:37:06'),
(12, 24, 9, 9, 'Agent', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. A eaque sequi veritatis laudantium blanditiis in tenetur at illo non, rerum nisi qui neque sunt est!', '2025-01-15 22:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `transaction_id` text NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `purchase_date` date NOT NULL DEFAULT curdate(),
  `expire_date` date NOT NULL,
  `currently_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `orders_ibfk_1` (`agent_id`),
  KEY `orders_ibfk_2` (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `agent_id`, `package_id`, `transaction_id`, `payment_method`, `paid_amount`, `status`, `purchase_date`, `expire_date`, `currently_active`) VALUES
(1, 3, 8, '3a4b1064b1263d3e2bcaf23a5622eb62', 'Stripe', 19.99, 'Completed', '2024-12-25', '2025-01-09', 0),
(3, 3, 10, '9ca247e007c0780458d9f25848c2d894', 'Stripe', 39.99, 'Completed', '2024-12-25', '2025-02-23', 0),
(5, 3, 10, '05791dfb0f1e5855c41672f152e26371', 'PayPal', 39.99, 'Completed', '2024-12-25', '2025-02-23', 1),
(16, 7, 8, '02dca02a002325dd8115dcd5e572d908', 'PayPal', 19.99, 'Completed', '2024-12-31', '2025-01-15', 1),
(19, 5, 9, '4e833253705a16b30fd6a49fbb03751d', 'PayPal', 29.99, 'Completed', '2024-12-31', '2025-01-30', 1),
(20, 9, 8, 'e5f4b72351be5d305c87600bc8d9d9d1', 'PayPal', 19.99, 'Completed', '2025-01-09', '2025-01-24', 1),
(21, 6, 9, 'adcc58d7de14f8d02faf62bc2b093d7a', 'PayPal', 29.99, 'Completed', '2025-01-11', '2025-02-10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allowed_days` int(11) NOT NULL DEFAULT 0,
  `allowed_properties` int(11) NOT NULL DEFAULT 0,
  `allowed_featured_properties` int(11) NOT NULL DEFAULT 0,
  `allowed_photos` int(11) NOT NULL DEFAULT 0,
  `allowed_videos` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`, `allowed_days`, `allowed_properties`, `allowed_featured_properties`, `allowed_photos`, `allowed_videos`) VALUES
(8, 'Basic', 19.99, 15, 3, 0, 3, 3),
(9, 'Standard', 29.99, 30, 5, 3, 5, 5),
(10, 'Gold', 39.99, 30, 15, 5, 15, 15);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `photo` text DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `posted_on` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `slug`, `photo`, `title`, `description`, `excerpt`, `status`, `posted_on`) VALUES
(1, 'the-rise-of-the-sustainable-housing-think-why-it-matters', '678ed028c52aa.jpg', 'The Rise of the Sustainable Housing: Think Why It Matters', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;With the new year underway, it&#039;s a great time to take stock of your finances and plan for the future. If you&#039;re looking to maximize your investments in 2023, here are some tips and strategies to help you get started.&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Diversify your portfolio: Don&#039;t put all your eggs in one basket. Spread your investments across different assets, such as stocks, bonds, real estate, and commodities, to reduce your overall risk.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider index funds: Index funds are a great way to gain exposure to the stock market without having to pick individual stocks. They offer low fees and broad diversification, making them a good choice for long-term investing.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay invested for the long term: Don&#039;t get caught up in short-term market fluctuations. Stick to your investment plan and remain invested for the long term, even during periods of market volatility.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Monitor your investments regularly: Keep a close eye on your investments and re-balance your portfolio as needed to ensure that you are aligned with your investment goals.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider working with a financial advisor: If you&#039;re not comfortable managing your investments on your own, consider working with a financial advisor. They can provide you with personalized advice and help you make informed investment decisions.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay informed: Stay up to date with the latest news and trends in the financial world, so you can make informed investment decisions. Read financial news and attend investment seminars to gain a deeper understanding of the market.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Invest in yourself: Finally, remember to invest in yourself. Whether it&#039;s through education, training, or professional development, investing in your personal growth can pay off in the long run.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;By following these tips and strategies, you can maximize your investment returns in 2023 and beyond. Good luck!&lt;/p&gt;', 'Explore the growing trend of sustainable housing and learn why it&#039;s more important than ever to invest in environmentally-friendly homes.', 1, '2025-01-20 22:36:38'),
(4, 'the-insider-039-s-guide-to-finding-your-dream-home-and-land', NULL, 'The Insider&#039;s Guide to Finding Your Dream Home and Land', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;With the new year underway, it&#039;s a great time to take stock of your finances and plan for the future. If you&#039;re looking to maximize your investments in 2023, here are some tips and strategies to help you get started.&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Diversify your portfolio: Don&#039;t put all your eggs in one basket. Spread your investments across different assets, such as stocks, bonds, real estate, and commodities, to reduce your overall risk.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider index funds: Index funds are a great way to gain exposure to the stock market without having to pick individual stocks. They offer low fees and broad diversification, making them a good choice for long-term investing.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay invested for the long term: Don&#039;t get caught up in short-term market fluctuations. Stick to your investment plan and remain invested for the long term, even during periods of market volatility.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Monitor your investments regularly: Keep a close eye on your investments and re-balance your portfolio as needed to ensure that you are aligned with your investment goals.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider working with a financial advisor: If you&#039;re not comfortable managing your investments on your own, consider working with a financial advisor. They can provide you with personalized advice and help you make informed investment decisions.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay informed: Stay up to date with the latest news and trends in the financial world, so you can make informed investment decisions. Read financial news and attend investment seminars to gain a deeper understanding of the market.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Invest in yourself: Finally, remember to invest in yourself. Whether it&#039;s through education, training, or professional development, investing in your personal growth can pay off in the long run.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;By following these tips and strategies, you can maximize your investment returns in 2023 and beyond. Good luck!&lt;/p&gt;', 'Get the inside scoop on how to find the perfect home for you and your family. Learn about the best neighborhoods, the latest home trends, and how to make an offer.', 1, '2025-01-21 07:18:45'),
(5, 'maximizing-your-property-investment-tips-for-success', '678f3c6f2b56e.jpg', 'Maximizing Your Property Investment: Tips for Success', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;With the new year underway, it&#039;s a great time to take stock of your finances and plan for the future. If you&#039;re looking to maximize your investments in 2023, here are some tips and strategies to help you get started.&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Diversify your portfolio: Don&#039;t put all your eggs in one basket. Spread your investments across different assets, such as stocks, bonds, real estate, and commodities, to reduce your overall risk.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider index funds: Index funds are a great way to gain exposure to the stock market without having to pick individual stocks. They offer low fees and broad diversification, making them a good choice for long-term investing.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay invested for the long term: Don&#039;t get caught up in short-term market fluctuations. Stick to your investment plan and remain invested for the long term, even during periods of market volatility.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Monitor your investments regularly: Keep a close eye on your investments and re-balance your portfolio as needed to ensure that you are aligned with your investment goals.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider working with a financial advisor: If you&#039;re not comfortable managing your investments on your own, consider working with a financial advisor. They can provide you with personalized advice and help you make informed investment decisions.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay informed: Stay up to date with the latest news and trends in the financial world, so you can make informed investment decisions. Read financial news and attend investment seminars to gain a deeper understanding of the market.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Invest in yourself: Finally, remember to invest in yourself. Whether it&#039;s through education, training, or professional development, investing in your personal growth can pay off in the long run.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;By following these tips and strategies, you can maximize your investment returns in 2023 and beyond. Good luck!&lt;/p&gt;', 'In this blog, you will learn how to make smart real estate investments that will maximize your returns and help you reach your financial goals.', 1, '2025-01-21 07:19:18'),
(6, 'the-top-10-most-popular-real-estate-markets-of-the-year', '6790146d8bed4.jpg', 'The Top 10 Most Popular Real Estate Markets of the Year', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;With the new year underway, it&#039;s a great time to take stock of your finances and plan for the future. If you&#039;re looking to maximize your investments in 2023, here are some tips and strategies to help you get started.&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Diversify your portfolio: Don&#039;t put all your eggs in one basket. Spread your investments across different assets, such as stocks, bonds, real estate, and commodities, to reduce your overall risk.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider index funds: Index funds are a great way to gain exposure to the stock market without having to pick individual stocks. They offer low fees and broad diversification, making them a good choice for long-term investing.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay invested for the long term: Don&#039;t get caught up in short-term market fluctuations. Stick to your investment plan and remain invested for the long term, even during periods of market volatility.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Monitor your investments regularly: Keep a close eye on your investments and re-balance your portfolio as needed to ensure that you are aligned with your investment goals.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider working with a financial advisor: If you&#039;re not comfortable managing your investments on your own, consider working with a financial advisor. They can provide you with personalized advice and help you make informed investment decisions.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay informed: Stay up to date with the latest news and trends in the financial world, so you can make informed investment decisions. Read financial news and attend investment seminars to gain a deeper understanding of the market.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Invest in yourself: Finally, remember to invest in yourself. Whether it&#039;s through education, training, or professional development, investing in your personal growth can pay off in the long run.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;By following these tips and strategies, you can maximize your investment returns in 2023 and beyond. Good luck!&lt;/p&gt;', 'This blog will provide you with a list of the hottest real estate markets in the country, as well as information on why they&#039;re so popular.', 1, '2025-01-21 22:41:01'),
(7, 'the-benefits-of-working-with-a-good-real-estate-agent', '6790149756ed8.jpg', 'The Benefits of Working with a Good Real Estate Agent', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;With the new year underway, it&#039;s a great time to take stock of your finances and plan for the future. If you&#039;re looking to maximize your investments in 2023, here are some tips and strategies to help you get started.&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Diversify your portfolio: Don&#039;t put all your eggs in one basket. Spread your investments across different assets, such as stocks, bonds, real estate, and commodities, to reduce your overall risk.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider index funds: Index funds are a great way to gain exposure to the stock market without having to pick individual stocks. They offer low fees and broad diversification, making them a good choice for long-term investing.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay invested for the long term: Don&#039;t get caught up in short-term market fluctuations. Stick to your investment plan and remain invested for the long term, even during periods of market volatility.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Monitor your investments regularly: Keep a close eye on your investments and re-balance your portfolio as needed to ensure that you are aligned with your investment goals.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider working with a financial advisor: If you&#039;re not comfortable managing your investments on your own, consider working with a financial advisor. They can provide you with personalized advice and help you make informed investment decisions.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay informed: Stay up to date with the latest news and trends in the financial world, so you can make informed investment decisions. Read financial news and attend investment seminars to gain a deeper understanding of the market.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Invest in yourself: Finally, remember to invest in yourself. Whether it&#039;s through education, training, or professional development, investing in your personal growth can pay off in the long run.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;By following these tips and strategies, you can maximize your investment returns in 2023 and beyond. Good luck!&lt;/p&gt;', 'Find out why working with a real estate agent can be a game-changer when it comes to buying or selling a home. Learn about the services they provide.', 1, '2025-01-21 22:41:27'),
(8, 'the-impact-of-interest-rates-on-the-real-estate-market', '679014cd2bc29.jpg', 'The Impact of Interest Rates on the Real Estate Market', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;With the new year underway, it&#039;s a great time to take stock of your finances and plan for the future. If you&#039;re looking to maximize your investments in 2023, here are some tips and strategies to help you get started.&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Diversify your portfolio: Don&#039;t put all your eggs in one basket. Spread your investments across different assets, such as stocks, bonds, real estate, and commodities, to reduce your overall risk.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider index funds: Index funds are a great way to gain exposure to the stock market without having to pick individual stocks. They offer low fees and broad diversification, making them a good choice for long-term investing.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay invested for the long term: Don&#039;t get caught up in short-term market fluctuations. Stick to your investment plan and remain invested for the long term, even during periods of market volatility.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Monitor your investments regularly: Keep a close eye on your investments and re-balance your portfolio as needed to ensure that you are aligned with your investment goals.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Consider working with a financial advisor: If you&#039;re not comfortable managing your investments on your own, consider working with a financial advisor. They can provide you with personalized advice and help you make informed investment decisions.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Stay informed: Stay up to date with the latest news and trends in the financial world, so you can make informed investment decisions. Read financial news and attend investment seminars to gain a deeper understanding of the market.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Invest in yourself: Finally, remember to invest in yourself. Whether it&#039;s through education, training, or professional development, investing in your personal growth can pay off in the long run.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;By following these tips and strategies, you can maximize your investment returns in 2023 and beyond. Good luck!&lt;/p&gt;', 'This blog will examine how the common interest rates can affect the real estate market and what it means for buyers and sellers.', 1, '2025-01-21 22:42:37');

-- --------------------------------------------------------

--
-- Table structure for table `post_views`
--

DROP TABLE IF EXISTS `post_views`;
CREATE TABLE IF NOT EXISTS `post_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `viewed_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_views`
--

INSERT INTO `post_views` (`id`, `post_id`, `ip_address`, `viewed_at`) VALUES
(14, 4, '127.0.0.1', '2025-01-21 20:22:32'),
(25, 1, '127.0.0.1', '2025-01-21 20:35:29'),
(26, 5, '127.0.0.1', '2025-01-21 20:36:01'),
(27, 8, '127.0.0.1', '2025-01-24 18:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `privacy`
--

DROP TABLE IF EXISTS `privacy`;
CREATE TABLE IF NOT EXISTS `privacy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `privacy`
--

INSERT INTO `privacy` (`id`, `title`, `text`, `status`) VALUES
(1, 'Privacy Policy', '&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Introduction&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;At ArefinDev, we are committed to protecting your privacy and personal information. This Privacy Policy (the &quot;Policy&quot;) explains how we collect, use, and disclose your personal information when you use our website and related services (collectively, the &quot;Services&quot;). By using our Services, you agree to the collection, use, and disclosure of your personal information as described in this Policy.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Information Collection&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;We collect information about you in various ways when you use our Services. This may include:&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Information you provide directly: We collect information that you provide to us directly, such as your name, email address, and other contact information.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Information we collect automatically: We collect information about your use of our Services automatically, such as your IP address, browser type, and device information.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Use of Information&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;We use the information we collect about you for the following purposes:&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;To provide the Services: We use your information to provide the Services to you.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;To improve the Services: We use your information to understand how you use our Services and to improve them.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;To communicate with you: We use your information to communicate with you, such as to respond to your inquiries and send you updates about the Services.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Disclosure of Information&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;We do not sell, rent, or otherwise disclose your personal information to third parties, except as described in this Policy. We may share your information with:&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Service providers: We may share your information with service providers who perform services on our behalf, such as hosting, data analysis, and customer support.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;Legal reasons: We may disclose your information if we believe it is necessary to comply with a law, regulation, legal process, or government request.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Security&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;We take reasonable measures to protect your personal information from unauthorized access, use, or disclosure. However, no security measures are perfect or impenetrable and we cannot guarantee the security of your personal information.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Changes to this Policy&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;We may update this Policy from time to time. If we make any material changes, we will notify you by email or by posting a notice on our website. Your continued use of our Services after we have notified you of any changes to this Policy indicates your acceptance of the changes.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Contact Us&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;If you have any questions or concerns about this Policy, please contact us at arefindev@gmail.com&lt;/p&gt;', 1);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `amenities` text DEFAULT NULL,
  `name` text NOT NULL,
  `slug` text NOT NULL,
  `description` text DEFAULT NULL,
  `featured_photo` text DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `purpose` text NOT NULL,
  `bedroom` tinyint(4) DEFAULT NULL,
  `bathroom` tinyint(4) DEFAULT NULL,
  `size` varchar(255) NOT NULL,
  `floor` tinyint(4) DEFAULT NULL,
  `garage` tinyint(4) DEFAULT NULL,
  `balcony` tinyint(4) DEFAULT NULL,
  `address` text NOT NULL,
  `built_year` date DEFAULT NULL,
  `map` text NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `posted_on` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `agent_id`, `location_id`, `type_id`, `amenities`, `name`, `slug`, `description`, `featured_photo`, `price`, `purpose`, `bedroom`, `bathroom`, `size`, `floor`, `garage`, `balcony`, `address`, `built_year`, `map`, `is_featured`, `posted_on`) VALUES
(1, 3, 20, 12, '12,5,14,9', 'Modern Villa', 'modern-villa', NULL, '676d112946999.jpg', '49.22', 'For Rent', NULL, NULL, '1200', NULL, NULL, NULL, '937 Jamajo Blvd, Orlando FL 32803', NULL, '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.2527998699!2d-74.14448787425354!3d40.697631233397885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2spl!4v1735200434107!5m2!1sen!2spl&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 0, '2024-12-26'),
(14, 5, 1, 12, '12,6,7,5,14,9,8,4', 'Park Towers South', 'park-towers-south', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;', '676fc40f27c50.jpg', '100.000', 'For Sale', 3, 4, '1200', 3, 1, 4, '315 W 57th St, New York, NY 10019', '2005-05-05', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.7563146190532!2d-73.9863354242865!3d40.76738413422364!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c258587ccef2bb%3A0x26f8e6cbf1ffbbbd!2s315%20W%2057th%20St%2C%20New%20York%2C%20NY%2010019%2C%20USA!5e0!3m2!1sen!2sbd!4v1697045038770!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 1, '2024-12-28'),
(16, 7, 21, 8, '12,6,7,5,14,9', 'Nice Condo in San Diago', 'nice-condo-in-san-diago', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;', '67705505b23f0.jpg', '2.500', 'For Rent', 4, 3, '783', 2, 1, 3, '1210 Botham Jean Blvd, Dallas, TX 75215', '1995-05-25', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3354.8399089364057!2d-96.79933252464295!3d32.76998218434604!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e990343afea6b%3A0x85ad20f4a289dedc!2s1210%20Botham%20Jean%20Blvd%2C%20Dallas%2C%20TX%2075215%2C%20USA!5e0!3m2!1sen!2sbd!4v1697045657127!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;\r\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;', 0, '2024-12-28'),
(17, 8, 22, 9, '12,6,7,5,14', 'Nice Villa in Washington', 'nice-villa-in-washington', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;', '6770560d023a2.jpg', '1.950', 'For Rent', 4, 4, '895', 2, 1, 3, '3702 Frankford Rd, Dallas, TX 75287', '2015-05-24', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3351.454752992814!2d-96.7666750246394!3d32.85968547975725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9f9a4aaabd3f%3A0x4050c905412fe466!2s5605%20Village%20Glen%20Dr%2C%20Dallas%2C%20TX%2075206%2C%20USA!5e0!3m2!1sen!2sbd!4v1697046098554!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 0, '2024-12-28'),
(21, 6, 20, 5, NULL, 'Apartment in New York', 'apartment-in-new-york', '&lt;div class=&quot;left-item&quot; style=&quot;box-sizing: border-box; margin-bottom: 30px; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;\r\n&lt;/div&gt;', '6771935860c64.jpg', '750.000', 'For Sale', NULL, 2, '95.43', NULL, NULL, NULL, '937 Jamajo Blvd, Orlando FL 32803', NULL, '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3351.454752992814!2d-96.7666750246394!3d32.85968547975725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9f9a4aaabd3f%3A0x4050c905412fe466!2s5605%20Village%20Glen%20Dr%2C%20Dallas%2C%20TX%2075206%2C%20USA!5e0!3m2!1sen!2sbd!4v1697046098554!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 1, '2024-12-29'),
(29, 6, 18, 11, '6,7,5,14,13', 'The Village Dallas', 'the-village-dallas', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;', '67746309b23b5.jpg', '2.900', 'For Rent', 3, 2, '698', 2, 1, 3, '5605 Village Glen Dr, Dallas, TX 75206', '2015-05-21', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3351.454752992814!2d-96.7666750246394!3d32.85968547975725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9f9a4aaabd3f%3A0x4050c905412fe466!2s5605%20Village%20Glen%20Dr%2C%20Dallas%2C%20TX%2075206%2C%20USA!5e0!3m2!1sen!2sbd!4v1697046098554!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 1, '2024-12-31'),
(30, 7, 18, 8, '6,7,5,14', 'Halston on Frankford', 'halston-on-frankford', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;', '677464b41e607.jpg', '3.100', 'For Rent', 5, 2, '900', 2, 1, 3, '3702 Frankford Rd, Dallas, TX 75287', '2023-08-18', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3351.454752992814!2d-96.7666750246394!3d32.85968547975725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9f9a4aaabd3f%3A0x4050c905412fe466!2s5605%20Village%20Glen%20Dr%2C%20Dallas%2C%20TX%2075206%2C%20USA!5e0!3m2!1sen!2sbd!4v1697046098554!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 0, '2024-12-31'),
(35, 6, 20, 5, NULL, 'FRANK 57 WEST', 'frank-57-west', '&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This is a beautiful and spacious property located in a prime location. The property boasts 3 bedrooms, 2 bathrooms, and a large living room with a fireplace. The kitchen is fully equipped with modern appliances and plenty of storage space.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The outdoor area includes a spacious deck, perfect for entertaining guests or relaxing after a long day. The large backyard is well-manicured and includes mature trees, providing plenty of shade and privacy.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The property also features a 2-car attached garage, offering additional storage space and convenience. The home is located in a peaceful and well-established neighborhood, close to schools, shopping, and public transportation.&lt;/p&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;This property would make the perfect home for a growing family or anyone looking for a spacious and comfortable place to call home. Don&#039;t miss out on the opportunity to own this beautiful property.&lt;/p&gt;', '677c465664da1.jpg', '2.350', 'For Rent', NULL, NULL, '125', NULL, NULL, NULL, '600 W 58th St, New York, NY 10019', NULL, '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3351.454752992814!2d-96.7666750246394!3d32.85968547975725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9f9a4aaabd3f%3A0x4050c905412fe466!2s5605%20Village%20Glen%20Dr%2C%20Dallas%2C%20TX%2075206%2C%20USA!5e0!3m2!1sen!2sbd!4v1697046098554!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 1, '2025-01-06'),
(36, 9, 20, 11, '7,5,14,18', 'Park Towers South', 'park-towers-south', NULL, '678036878cb8b.jpg', '5000.000.000', 'For Sale', NULL, NULL, '900.00', NULL, NULL, NULL, '315 W 57th St, New York, NY 10019', '2019-05-18', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3351.454752992814!2d-96.7666750246394!3d32.85968547975725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864e9f9a4aaabd3f%3A0x4050c905412fe466!2s5605%20Village%20Glen%20Dr%2C%20Dallas%2C%20TX%2075206%2C%20USA!5e0!3m2!1sen!2sbd!4v1697046098554!5m2!1sen!2sbd&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;', 0, '2025-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `property_photos`
--

DROP TABLE IF EXISTS `property_photos`;
CREATE TABLE IF NOT EXISTS `property_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `photo` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `property_photos`
--

INSERT INTO `property_photos` (`id`, `property_id`, `photo`) VALUES
(19, 1, '676de1e492e2d.jpg'),
(20, 1, '676de21375fee.jpg'),
(34, 14, '676fc4e49b038.jpg'),
(38, 17, '67705b31a8a28.jpg'),
(40, 21, '677193aa5422b.jpg'),
(41, 21, '677198fa67937.jpg'),
(42, 21, '6771993142712.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `property_videos`
--

DROP TABLE IF EXISTS `property_videos`;
CREATE TABLE IF NOT EXISTS `property_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `property_videos`
--

INSERT INTO `property_videos` (`id`, `property_id`, `code`) VALUES
(8, 1, 'bewwWLNO3q8'),
(10, 1, 'T-5uw2BYzRI'),
(17, 14, 'DGCshpSoeoc'),
(26, 17, '51ALckqxswY'),
(28, 21, 'mr-uNuzVECg'),
(30, 21, 'bAnqNERdXLU');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) NOT NULL DEFAULT 'https://placehold.co/600x200?text=Logo',
  `favicon` text NOT NULL,
  `banner` text NOT NULL,
  `hero_heading` varchar(255) NOT NULL,
  `hero_subheading` varchar(255) NOT NULL,
  `hero_photo` varchar(255) NOT NULL,
  `featured_property_heading` varchar(255) NOT NULL,
  `featured_property_subheading` varchar(255) NOT NULL,
  `featured_property_status` tinyint(1) NOT NULL DEFAULT 0,
  `why_choose_heading` varchar(255) NOT NULL,
  `why_choose_subheading` varchar(255) NOT NULL,
  `why_choose_photo` varchar(255) NOT NULL,
  `why_choose_status` tinyint(1) NOT NULL DEFAULT 0,
  `agent_heading` varchar(255) NOT NULL,
  `agent_subheading` varchar(255) NOT NULL,
  `agent_status` tinyint(1) NOT NULL DEFAULT 0,
  `location_heading` varchar(255) NOT NULL,
  `location_subheading` varchar(255) NOT NULL,
  `location_status` tinyint(1) NOT NULL DEFAULT 0,
  `testimonial_heading` varchar(255) NOT NULL,
  `testimonial_photo` varchar(255) NOT NULL,
  `testimonial_status` tinyint(1) NOT NULL DEFAULT 0,
  `post_heading` varchar(255) NOT NULL,
  `post_subheading` varchar(255) NOT NULL,
  `post_status` tinyint(1) NOT NULL DEFAULT 0,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `facebook` text DEFAULT NULL,
  `twitter` text DEFAULT NULL,
  `youtube` text DEFAULT NULL,
  `linkedin` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `copyright` varchar(255) NOT NULL,
  `map` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `logo`, `favicon`, `banner`, `hero_heading`, `hero_subheading`, `hero_photo`, `featured_property_heading`, `featured_property_subheading`, `featured_property_status`, `why_choose_heading`, `why_choose_subheading`, `why_choose_photo`, `why_choose_status`, `agent_heading`, `agent_subheading`, `agent_status`, `location_heading`, `location_subheading`, `location_status`, `testimonial_heading`, `testimonial_photo`, `testimonial_status`, `post_heading`, `post_subheading`, `post_status`, `address`, `email`, `phone`, `facebook`, `twitter`, `youtube`, `linkedin`, `instagram`, `copyright`, `map`) VALUES
(1, '679c8a98bb7ec.png', '679c884a776b1.png', '679cac271b721.jpg', 'Discover Your New Home', 'You can get your desired awesome properties, homes, condos etc. here by name, category or location.', '679cdefc16860.jpg', 'Featured Properties', 'Find out the awesome properties that you must love', 1, 'Why Choose Us', 'Describing why we are best in the property business', '679d2b12cc2c0.jpg', 1, 'Agents', 'Meet our expert property agents from the following list', 1, 'Locations', 'Check out all the properties of important locations', 1, 'Our Happy Clients', '679d4f048687f.jpg', 1, 'Latest News', 'Check our latest news from the following section', 1, '34 Antiger Lane, USA, 12937', 'contact@arefindev.com', '122-222-1212', 'https://www.facebook.com', 'https://www.twitter.com', 'https://www.youtube.com', 'https://www.linkedin.com', 'https://www.instagram.com', 'Copyright 2023, ArefinDev. All Rights Reserved.', '&lt;p&gt;&lt;iframe style=&quot;border: 0;&quot; src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.2527998699!2d-74.14448787425354!3d40.697631233397885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2spl!4v1735200434107!5m2!1sen!2spl&quot; width=&quot;600&quot; height=&quot;450&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `ip_address`, `token`, `status`, `created_at`) VALUES
(2, 'customer@mail.com', '::1', NULL, 1, '2025-01-25 23:15:15'),
(3, 'customer_2@mail.com', '::1', NULL, 1, '2025-01-26 00:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

DROP TABLE IF EXISTS `terms`;
CREATE TABLE IF NOT EXISTS `terms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `title`, `text`, `status`) VALUES
(1, 'Terms and Conditions', '&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Introduction&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;Welcome to ArefinDev (the &quot;Company&quot;). The following terms and conditions (the &quot;Terms&quot;) govern your use of our website, products, and services (collectively referred to as the &quot;Services&quot;). By accessing or using our Services, you agree to be bound by these Terms and our Privacy Policy, which is incorporated into these Terms. If you do not agree to these Terms, you may not use our Services.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Use of Services&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;You may use our Services only for lawful purposes and in accordance with these Terms. You agree not to use our Services:&lt;/p&gt;\r\n&lt;ol style=&quot;box-sizing: border-box; padding-left: 2rem; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;In any way that violates any applicable federal, state, local, or international law or regulation.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;For the purpose of exploiting, harming, or attempting to exploit or harm minors in any way by exposing them to inappropriate content, asking for personally identifiable information, or otherwise.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;To transmit, or procure the sending of, any advertising or promotional material, including any &quot;junk mail,&quot; &quot;chain letter,&quot; &quot;spam,&quot; or any other similar solicitation.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;To impersonate or attempt to impersonate the Company, a Company employee, another user, or any other person or entity.&lt;/li&gt;\r\n&lt;li style=&quot;box-sizing: border-box;&quot;&gt;To use, display, mirror, or frame the Services, or any individual element within the Services, the Company&#039;s name, trademark, logo, or other proprietary information, or the layout and design of any page or form contained on a page, without the Company&#039;s express written consent.&lt;/li&gt;\r\n&lt;/ol&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Intellectual Property&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;The Services and its entire contents, features, and functionality (including but not limited to all information, software, text, displays, images, video, and audio, and the design, selection, and arrangement thereof), are owned by the Company, its licensors, or other providers of such material and are protected by United States and international copyright, trademark, patent, trade secret, and other intellectual property or proprietary rights laws. These Terms permit you to use the Services for your personal, non-commercial use only. You hereby acknowledge that any unauthorized use may violate such laws and the Terms.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Disclaimer of Warranties&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;THE SERVICES ARE PROVIDED &quot;AS IS&quot; WITHOUT WARRANTY OF ANY KIND. THE COMPANY DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. THE COMPANY DOES NOT WARRANT THAT THE FUNCTIONS CONTAINED IN THE SERVICES WILL BE UNINTERRUPTED OR ERROR-FREE, THAT DEFECTS WILL BE CORRECTED, OR THAT THE SERVICES OR THE SERVER THAT MAKES THE SERVICES AVAILABLE ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Limitation of Liability&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;IN NO EVENT WILL THE COMPANY BE LIABLE FOR ANY INDIRECT, INCIDENTAL, PUNITIVE, EXEMPLARY, OR CONSEQUENTIAL DAMAGES, INCLUDING LOST PROFITS, LOSS OF USE, LOSS OF DATA, OR COSTS OF PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES, HOWEVER CAUSED AND UNDER ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THE SERVICES, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.&lt;/p&gt;\r\n&lt;h4 style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; color: #212529; font-size: calc(1.275rem + 0.3vw); font-family: Roboto, sans-serif; background-color: #ffffff;&quot;&gt;Indemnification&lt;/h4&gt;\r\n&lt;p style=&quot;box-sizing: border-box; margin-top: 0px; margin-bottom: 1rem; color: #212529; font-family: Roboto, sans-serif; font-size: 14px; background-color: #ffffff;&quot;&gt;You agree to indemnify, defend, and hold harmless&lt;/p&gt;', 1);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `photo` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `full_name`, `designation`, `comment`, `photo`, `status`) VALUES
(1, 'Robert Krol', 'CEO, ABC Company', 'I recently worked with Patrick Johnson on purchasing my dream home and I couldn&#039;t have asked for a better experience. Patrick Johnson was knowledgeable, professional, and truly cared about finding me the perfect property. They were always available to answer my questions and made the entire process stress-free. I highly recommend Patrick Johnson to anyone looking to buy or sell a property!', '678d47055299f.jpg', 1),
(2, 'Sal Harvey', 'Director, DEF Company', 'I recently worked with Patrick Johnson on purchasing my dream home and I couldn&#039;t have asked for a better experience. Patrick Johnson was knowledgeable, professional, and truly cared about finding me the perfect property. They were always available to answer my questions and made the entire process stress-free. I highly recommend Patrick Johnson to anyone looking to buy or sell a property!', '678d46fddefc9.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(5, 'Apartment'),
(6, 'Bungalow'),
(7, 'Cabin'),
(8, 'Condo'),
(9, 'Cottage'),
(10, 'Duplex'),
(11, 'Townhouse'),
(12, 'Villa');

-- --------------------------------------------------------

--
-- Table structure for table `why_choose_items`
--

DROP TABLE IF EXISTS `why_choose_items`;
CREATE TABLE IF NOT EXISTS `why_choose_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `why_choose_items`
--

INSERT INTO `why_choose_items` (`id`, `icon`, `heading`, `text`, `status`) VALUES
(1, 'fas fa-briefcase', 'Years of Experience', 'With decades of combined experience in the industry, our agents have the expertise and knowledge to provide you with a seamless home-buying experience.\n\n', 1),
(2, 'fas fa-search', 'Competitive Prices', 'We understand that buying a home is a significant investment, which is why we strive to offer competitive prices to our clients.', 1),
(12, 'fas fa-share-alt', 'Responsive Communication', 'Our responsive agents are here to answer your questions and address your concerns, ensuring a smooth and stress-free home-buying experience.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `customer_id`, `property_id`) VALUES
(16, 9, 21),
(18, 9, 29),
(19, 9, 16),
(22, 9, 30),
(39, 10, 21);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `message_replies_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `message_replies_ibfk_3` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `post_views`
--
ALTER TABLE `post_views`
  ADD CONSTRAINT `post_views_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `type_id` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `property_photos`
--
ALTER TABLE `property_photos`
  ADD CONSTRAINT `property_id` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `property_videos`
--
ALTER TABLE `property_videos`
  ADD CONSTRAINT `property_videos_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
