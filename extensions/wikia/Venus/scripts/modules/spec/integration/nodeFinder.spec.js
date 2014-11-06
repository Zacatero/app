describe( 'moduleInsertion', function(){
	'use strict';

	var moduleInsertion,
		articleMock = document.createElement('div'),
		extenderMock = document.createElement('div'),
		h2Mock = document.createElement('h2'),
		cloneNode,
		id = 0;


	articleMock.id = 'article-content';
	extenderMock.style.height = '200px';

	for(id = 0; id < 10; id ++) {
		cloneNode = extenderMock.cloneNode();
		articleMock.appendChild(cloneNode);
		h2Mock.id = 'h2-' + id;
		cloneNode = h2Mock.cloneNode();
		articleMock.appendChild(cloneNode);
	}

	document.body.appendChild(articleMock);

	moduleInsertion = modules['venus.moduleInsertion'](document);

	it('header should returned', function(){
		var header = moduleInsertion.findElementByOffsetTop(articleMock, 'h2', 100);
		expect(header.id).toBe('h2-0');

		header = moduleInsertion.findElementByOffsetTop(articleMock, 'h2', 300);
		expect(header.id).toBe('h2-1');

		header = moduleInsertion.findElementByOffsetTop(articleMock, 'h2', 1000);
		expect(header.id).toBe('h2-4');
	});
});
