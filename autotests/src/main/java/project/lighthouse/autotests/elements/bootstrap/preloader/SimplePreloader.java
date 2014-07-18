package project.lighthouse.autotests.elements.bootstrap.preLoader;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.preLoader.abstraction.AbstractPreLoader;

/**
 * Simple bootstrap preloader
 */
public class SimplePreloader extends AbstractPreLoader {

    public SimplePreloader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return null;
    }
}
