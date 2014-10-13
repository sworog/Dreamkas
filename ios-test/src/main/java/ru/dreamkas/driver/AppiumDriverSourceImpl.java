package ru.dreamkas.driver;

import io.appium.java_client.AppiumDriver;
import io.appium.java_client.ios.IOSDriver;
import net.thucydides.core.webdriver.DriverSource;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.remote.CapabilityType;
import org.openqa.selenium.remote.DesiredCapabilities;

import java.net.MalformedURLException;
import java.net.URL;

public class AppiumDriverSourceImpl implements DriverSource{

    private static final String APPIUM_URL = System.getProperty("appium.server.url");
    private static final String PATH_TO_FILE = System.getProperty("appium.path.to.file");

    @Override
    public WebDriver newDriver() {
        DesiredCapabilities capabilities = new DesiredCapabilities();
        capabilities.setCapability(CapabilityType.BROWSER_NAME, "");
        capabilities.setCapability("platformName", "iOS");
        capabilities.setCapability("deviceName","iPad Simulator");
        capabilities.setCapability("platformVersion", "7.1");
        capabilities.setCapability("app", PATH_TO_FILE);
        capabilities.setCapability("unicodeKeyboard", true);
        try {
            return new IOSDriver(new URL(APPIUM_URL), capabilities);
        } catch (MalformedURLException e) {
            throw new AssertionError(e);
        }
    }

    @Override
    public boolean takesScreenshots() {
        return true;
    }
}
