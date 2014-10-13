package ru.dreamkas.elements.preLoader;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.preLoader.abstraction.AbstractPreLoader;

public class CheckBoxPreLoader extends AbstractPreLoader {

    public CheckBoxPreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//*[@class='preloader_stripes']";
    }
}
