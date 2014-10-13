package ru.dreamkas.elements.bootstrap;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.preLoader.abstraction.AbstractPreLoader;

public class WaitForModalWindowClose extends AbstractPreLoader {

    public WaitForModalWindowClose(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//body[@class='modal-open']";
    }
}
