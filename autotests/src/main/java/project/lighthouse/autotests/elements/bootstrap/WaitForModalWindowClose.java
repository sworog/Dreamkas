package project.lighthouse.autotests.elements.bootstrap;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.preLoader.abstraction.AbstractPreLoader;

public class WaitForModalWindowClose extends AbstractPreLoader {

    public WaitForModalWindowClose(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//body[@class='modal-open']";
    }
}
