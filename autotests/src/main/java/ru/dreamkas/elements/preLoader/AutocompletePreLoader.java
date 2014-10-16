package ru.dreamkas.elements.preLoader;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.elements.preLoader.abstraction.AbstractPreLoader;

public class AutocompletePreLoader extends AbstractPreLoader {

    public AutocompletePreLoader(WebDriver driver) {
        super(driver);
    }

    @Override
    public String getXpath() {
        return "//*[contains(@class, 'preloader_stripes')]";
    }
}
