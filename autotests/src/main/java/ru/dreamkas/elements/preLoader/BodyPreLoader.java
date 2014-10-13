package ru.dreamkas.elements.preLoader;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.preLoader.abstraction.AbstractPreLoader;

public class BodyPreLoader extends AbstractPreLoader {

    public BodyPreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//body[@status='loading']";
    }
}
