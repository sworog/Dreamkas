package project.lighthouse.autotests.elements.preLoader;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.preLoader.abstraction.AbstractPreLoader;

public class CheckBoxPreLoader extends AbstractPreLoader {

    public CheckBoxPreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//*[@class='preloader_stripes']";
    }
}
