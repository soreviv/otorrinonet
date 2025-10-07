import os
from playwright.sync_api import sync_playwright, Page, expect

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()

    # Get the absolute path to the HTML file
    file_path = os.path.abspath("contacto.html")

    # Navigate to the local HTML file
    page.goto(f"file://{file_path}")

    # Locate the iframe and scroll it into view
    map_locator = page.locator('iframe[src*="google.com/maps/embed"]')
    map_locator.scroll_into_view_if_needed()

    # Wait for the map to be visible
    map_iframe = page.frame_locator('iframe[src*="google.com/maps/embed"]')
    expect(map_iframe.locator('body')).to_be_visible()

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)