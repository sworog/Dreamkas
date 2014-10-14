package ru.dreamkas.driver;

import net.thucydides.core.webdriver.DriverSource;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.remote.CapabilityType;
import org.openqa.selenium.remote.DesiredCapabilities;

import java.net.MalformedURLException;
import java.net.URL;

import io.appium.java_client.android.AndroidDriver;

public class AppiumDriverSourceImpl implements DriverSource {

    private static final String APPIUM_URL = System.getProperty("appium.server.url");
    private static final String PATH_TO_FILE = System.getProperty("appium.path.to.file");

    @Override
    public WebDriver newDriver() {
        DesiredCapabilities capabilities = new DesiredCapabilities();
        capabilities.setCapability(CapabilityType.BROWSER_NAME, "");
        capabilities.setCapability("platformName", "Android");
        capabilities.setCapability("deviceName","Android Emulator");
        capabilities.setCapability("platformVersion", "4.4");
        capabilities.setCapability("app", PATH_TO_FILE);
        capabilities.setCapability("appPackage", "ru.dreamkas.pos.debug");
        capabilities.setCapability("appActivity", "ru.dreamkas.pos.view.activities.LoginActivity_");
        capabilities.setCapability("unicodeKeyboard", true);
        try {
            return new AndroidDriver(new URL(APPIUM_URL), capabilities);
        } catch (MalformedURLException e) {
            throw new AssertionError(e);
        }
    }

    @Override
    public boolean takesScreenshots() {
        return true;
    }
}

