package project.lighthouse.autotests.elements.preLoader;

import org.openqa.selenium.WebDriver;

public class BodyPreLoader extends AbstractPreLoader {

    public BodyPreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//body[contains(@class, 'preloader_spinner')]";
    }
}
