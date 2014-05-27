package project.lighthouse.autotests.thucydides;

import net.thucydides.core.ThucydidesSystemProperty;
import net.thucydides.core.util.EnvironmentVariables;
import net.thucydides.core.webdriver.ThucydidesWebDriverEventListener;
import net.thucydides.core.webdriver.WebDriverFacade;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.remote.RemoteWebDriver;

public class RemoteWebDriverEventListener implements ThucydidesWebDriverEventListener {

    private EnvironmentVariables environmentVariables;

    public RemoteWebDriverEventListener(EnvironmentVariables environmentVariables) {
        this.environmentVariables = environmentVariables;
    }

    @Override
    public void driverCreatedIn(WebDriver driver) {
        if (driver instanceof WebDriverFacade) {
            driver = ((WebDriverFacade) driver).getProxiedDriver();
        }

        if (driver instanceof RemoteWebDriver) {
            Dimension dimension = new Dimension(
                environmentVariables.getPropertyAsInteger(ThucydidesSystemProperty.SNAPSHOT_WIDTH, ThucydidesSystemProperty.DEFAULT_WIDTH),
                environmentVariables.getPropertyAsInteger(ThucydidesSystemProperty.SNAPSHOT_HEIGHT, ThucydidesSystemProperty.DEFAULT_HEIGHT)
            );
            driver.manage().window().setSize(dimension);
        }
    }
}
