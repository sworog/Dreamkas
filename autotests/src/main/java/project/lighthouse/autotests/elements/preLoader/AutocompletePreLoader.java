package project.lighthouse.autotests.elements.preLoader;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.preLoader.abstraction.AbstractPreLoader;

public class AutocompletePreLoader extends AbstractPreLoader {

    public AutocompletePreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//*[contains(@class, 'preloader_stripes')]";
    }
}
