package dreamkas.driver;

import io.appium.java_client.AppiumDriver;
import net.thucydides.core.webdriver.DriverSource;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.remote.CapabilityType;
import org.openqa.selenium.remote.DesiredCapabilities;

import java.net.MalformedURLException;
import java.net.URL;

public class AppiumDriverSourceImpl implements DriverSource {

    @Override
    public WebDriver newDriver() {
//        TODO Need to provide the path for apk file
//        File classpathRoot = new File("C:\\");
//        File appDir = new File(classpathRoot, "android");
//        File app = new File(appDir, "app-debug.apk");
//        Workaround for apk location
        String appPath = "D:\\dev\\lighthouse\\lighthouse\\android-client\\DreamKas\\app\\build\\outputs\\apk\\app-debug.apk";
        DesiredCapabilities capabilities = new DesiredCapabilities();
        capabilities.setCapability(CapabilityType.BROWSER_NAME, "");
        capabilities.setCapability("platformName", "Android");
        capabilities.setCapability("deviceName","Android Emulator");
        capabilities.setCapability("platformVersion", "4.4");
//        Setting the app file path
//        capabilities.setCapability("app", app.getAbsolutePath());
//        Setting the workaround apk location
        capabilities.setCapability("app", appPath);
        capabilities.setCapability("appPackage", "ru.dreamkas.pos.debug");
        capabilities.setCapability("appActivity", "ru.dreamkas.pos.view.activities.LoginActivity_");
        try {
            return new AppiumDriver(new URL("http://127.0.0.1:4723/wd/hub"), capabilities);
        } catch (MalformedURLException e) {
            throw new AssertionError(e);
        }
    }

    @Override
    public boolean takesScreenshots() {
        return true;
    }
}

