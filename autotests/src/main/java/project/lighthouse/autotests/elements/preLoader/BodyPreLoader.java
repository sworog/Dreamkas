package project.lighthouse.autotests.elements.preLoader;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.preLoader.abstraction.AbstractPreLoader;

public class BodyPreLoader extends AbstractPreLoader {

    public BodyPreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//body[contains(@class, 'preloader_spinner')]";
    }
}
